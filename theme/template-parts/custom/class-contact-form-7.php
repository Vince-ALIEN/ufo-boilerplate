<?php
/**
 * Contact Form 7 specific functionality
 *
 * @package Ufo_Boilerplate
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('UFO_Contact_Form7')) :
class UFO_Contact_Form7 {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
        add_filter('wpcf7_form_elements', [$this, 'customize_cf7_elements']);
        add_filter('wpcf7_validate_text', [$this, 'validate_honeypot'], 20, 2);
        add_filter('wpcf7_validate_text*', [$this, 'validate_honeypot'], 20, 2);
        add_action('wp_enqueue_scripts', [$this, 'handle_cf7_loading'], 77);

        $this->setup_spam_protection();
    }

    private function setup_spam_protection() {
        add_filter('wpcf7_spam', function($spam) {
            if (!$spam) {
                /** @suppress P1009 WPCF7_Submission is defined by the Contact Form 7 plugin */
                $submission = WPCF7_Submission::get_instance();
                if ($submission) {
                    $posted_data = $submission->get_posted_data();

                    if (empty($_SERVER['HTTP_USER_AGENT']) ||
                        empty($_SERVER['HTTP_ACCEPT']) ||
                        !empty($posted_data['honeypot'])) {
                        $spam = true;
                    }

                    $time = time();
                    $form_time = isset($_POST['_wpcf7_time']) ? (int)$_POST['_wpcf7_time'] : 0;
                    if ($time - $form_time < 5) {
                        $spam = true;
                    }
                }
            }
            return $spam;
        });
    }

    public function handle_cf7_loading() {
        global $post;

        $has_cf7 = false;

        if ($post) {
            // Vérifier si le contenu contient un shortcode CF7
            if (has_shortcode($post->post_content, 'contact-form-7')) {
                $has_cf7 = true;
            }

            // Vérifier si le contenu contient un bloc CF7
            if (has_block('contact-form-7/contact-form-selector', $post)) {
                $has_cf7 = true;
            }
        }

        // Désactiver CF7 si aucun formulaire n'est présent sur la page
        if (!$has_cf7) {
            wp_dequeue_script('contact-form-7');
            wp_dequeue_style('contact-form-7');
        }
    }

    public function customize_cf7_elements($content) {
        if (empty($content)) {
            return '';
        }

        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        libxml_use_internal_errors(true);

        $content = htmlspecialchars_decode($content, ENT_QUOTES | ENT_HTML5);
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $dom->loadHTML('<?xml encoding="utf-8">' . $content,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        /** @suppress P1009 WPCF7_ContactForm is defined by the Contact Form 7 plugin */
        $current_form = class_exists('WPCF7_ContactForm') ? WPCF7_ContactForm::get_current() : null;
        $is_newsletter = $current_form && str_contains((string) $current_form->prop('additional_settings'), 'cf7-newsletter');

        if ($is_newsletter) {
            $elements_config = [
                '//span[contains(@class, "wpcf7-form-control-wrap")]' => 'contents',
                '//input[@type="email"]'  => 'flex-1 py-3 px-4 border-none outline-none text-sm text-slate-900 bg-transparent',
                '//input[@type="submit"]' => 'bg-secondary text-background py-3 px-6 text-sm font-medium whitespace-nowrap hover:opacity-90 transition-opacity cursor-pointer',
            ];

            // Supprimer le spinner CF7 et la classe has-spinner du submit
            foreach (iterator_to_array($xpath->query('//span[contains(@class, "wpcf7-spinner")]')) as $el) {
                if ($el instanceof DOMElement) {
                    $el->parentNode->removeChild($el);
                }
            }
            foreach (iterator_to_array($xpath->query('//input[@type="submit"]')) as $el) {
                if ($el instanceof DOMElement) {
                    $el->setAttribute('class', trim(preg_replace('/\bhas-spinner\b/', '', $el->getAttribute('class'))));
                }
            }

            // Supprimer l'attribut size qui force une largeur fixe
            foreach (iterator_to_array($xpath->query('//input[@type="email"]')) as $el) {
                if ($el instanceof DOMElement) {
                    $el->removeAttribute('size');
                }
            }

            // Supprimer les <p> et <br> ajoutés par CF7
            foreach (iterator_to_array($xpath->query('//br')) as $br) {
                $br->parentNode->removeChild($br);
            }
            foreach (iterator_to_array($xpath->query('//p')) as $p) {
                $parent = $p->parentNode;
                while ($p->firstChild) {
                    $parent->insertBefore($p->firstChild, $p);
                }
                $parent->removeChild($p);
            }
        } else {
            $input_base = 'block w-full bg-slate-100 text-slate-900 border border-background/[.09] rounded-md font-opensans text-sm py-[0.65rem] px-[0.9rem] transition-all duration-200 focus:outline-none focus:border-barossa-800 focus:ring-[3px] focus:ring-barossa-800/18 placeholder:text-slate-300';

            $elements_config = [
                '//span[contains(@class, "wpcf7-form-control-wrap")]' => 'block w-full',
                '//input[@type="text"]'   => $input_base . ' mb-4',
                '//input[@type="email"]'  => $input_base . ' disabled:pointer-events-none mb-4',
                '//textarea'              => $input_base . ' disabled:opacity-50 disabled:pointer-events-none mb-4 h-48 resize-y',
                '//select'                => $input_base . ' disabled:pointer-events-none mb-4 cursor-pointer',
                '//input[@type="submit"]' => 'inline-flex items-center gap-3 is-style-primary btn cursor-pointer',
            ];
        }

        foreach ($elements_config as $query => $classes) {
            $elements = $xpath->query($query);
            foreach ($elements as $element) {
                if (!$element instanceof DOMElement) {
                    continue;
                }
                $currentClass = $element->getAttribute('class');
                $element->setAttribute('class', trim($currentClass . ' ' . $classes));
            }
        }

        // Remplace les <input type="submit"> par des <button> avec icône Lucide
        foreach (iterator_to_array($xpath->query('//input[@type="submit"]')) as $submit) {
            if (!$submit instanceof DOMElement) {
                continue;
            }

            $label = $submit->getAttribute('value') ?: 'Envoyer';

            $button = $dom->createElement('button');
            $button->setAttribute('type', 'submit');
            $button->setAttribute('class', $submit->getAttribute('class'));

            $icon = $dom->createElement('i');
            $icon->setAttribute('data-lucide', 'send');
            $icon->setAttribute('class', 'size-4');
            $icon->setAttribute('aria-hidden', 'true');
            $button->appendChild($icon);
            $button->appendChild($dom->createTextNode(' ' . $label));

            $submit->parentNode->replaceChild($button, $submit);
        }

        $honeypots = $xpath->query('//input[@name="honeypot"]');
        foreach ($honeypots as $honeypot) {
            if (!$honeypot instanceof DOMElement) {
                continue;
            }
            $honeypot->setAttribute('class', 'hidden');
            $honeypot->setAttribute('tabindex', '-1');
            $honeypot->setAttribute('autocomplete', 'off');
        }

        $output = $dom->saveHTML();
        $output = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(
            ['<?xml encoding="utf-8">', '</html>', '<html>', '<body>', '</body>'],
            '',
            $output
        ));

        return '<div class="bg-slate-200 rounded-xl py-2 px-6 mx-4">' . $output . '</div>';
    }

    public function validate_honeypot($result, $tag) {
        if ('honeypot' == $tag->name && !empty($_POST['honeypot'])) {
            $result->invalidate($tag, 'Spam détecté');
        }
        return $result;
    }
}
endif;

// Initialize
add_action('init', [UFO_Contact_Form7::get_instance(), 'init']);

add_action('wpcf7_validate_textarea','cwpai_custom_textarea_validation_filter', 10, 2);

if (!function_exists('cwpai_custom_textarea_validation_filter')) :
function cwpai_custom_textarea_validation_filter($result, $tag) {
    // Get the form input
    $form_input = isset($_POST[$tag->name]) ? $_POST[$tag->name] : '';

    // Define spam words
    $spam_words = array('spam', 'sex', 'escort', 'viagra', 'cialis', 'xanax', 'loan', 'credit', 'claim', 'won', 'selected', 'congratulations', 'free', 'off', 'save', 'plans', 'sex');

    // Check if any spam word exists in the input
    foreach($spam_words as $spam_word) {
        if(stripos($form_input, $spam_word) !== false) {
            // Add validation error
            $result->invalidate($tag, 'Please remove inappropriate words from your message.');
            break;
        }
    }
    return $result;
}
endif;
