<?php

namespace App\Helpers;

use DOMDocument;
use DOMElement;

class SanitizeHelper
{
    /**
     * Safely clean HTML input to prevent XSS using DOM parsing.
     *
     * @param string|null $html
     * @return string
     */
    public static function clean($html = null)
    {
        if (empty($html)) {
            return '';
        }

        // 1. Strip dangerous tags first to reduce the attack surface
        $allowedTags = '<p><a><b><i><u><strong><em><h1><h2><h3><h4><h5><h6><ul><ol><li><br><span><div><img><table><tr><td><th><tbody><thead><tfoot><hr><blockquote><pre><code><iframe><video><audio><source>';
        $html = strip_tags($html, $allowedTags);

        // 2. Load into DOMDocument for attribute parsing (much safer than Regex)
        $dom = new DOMDocument();
        
        // Suppress warnings from malformed HTML (common with CMS input)
        libxml_use_internal_errors(true);
        
        // Wrap in a div with UTF-8 meta to ensure proper encoding and parsing
        $wrappedHtml = '<html><head><meta charset="utf-8"></head><body><div>' . $html . '</div></body></html>';
        
        // Load the HTML
        $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // 3. Iterate through all elements to sanitize attributes
        $nodes = $dom->getElementsByTagName('*');
        
        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $attributesToRemove = [];
            
            foreach ($node->attributes as $attr) {
                $attrName = strtolower($attr->nodeName);
                $attrValue = strtolower(trim($attr->nodeValue));
                
                // Remove inline event handlers (onclick, onmouseover, etc.)
                if (str_starts_with($attrName, 'on')) {
                    $attributesToRemove[] = $attr->nodeName;
                    continue;
                }
                
                // Block srcdoc completely as it can contain arbitrary HTML and scripts
                if ($attrName === 'srcdoc') {
                    $attributesToRemove[] = $attr->nodeName;
                    continue;
                }
                
                // Block javascript: data: and vbscript: URIs in href, src, formaction, background
                if (in_array($attrName, ['href', 'src', 'formaction', 'background', 'action', 'poster'])) {
                    // Normalize value to detect obfuscated protocols
                    $normalizedValue = preg_replace('/[\x00-\x20\s\t\r\n]+/', '', $attrValue);
                    
                    if (str_starts_with($normalizedValue, 'javascript:') || 
                        str_starts_with($normalizedValue, 'data:') || 
                        str_starts_with($normalizedValue, 'vbscript:')) {
                        // Allow safe base64 images only
                        if (str_starts_with($normalizedValue, 'data:image/')) {
                            continue;
                        }
                        $attributesToRemove[] = $attr->nodeName;
                        continue;
                    }
                }
            }
            
            // Remove the bad attributes from the node
            foreach ($attributesToRemove as $attrName) {
                $node->removeAttribute($attrName);
            }
        }

        // 4. Extract body inner HTML without the wrapper
        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body || !$body->firstChild) {
            return '';
        }

        // Extract contents of wrapper div
        $wrapper = $body->firstChild;
        $cleanHtml = '';
        foreach ($wrapper->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        return $cleanHtml;
    }

    /**
     * Format HTML content: clean empty paragraphs, apply data-aos to key elements without duplication, and lazy-load images.
     *
     * @param string|null $html
     * @return string
     */
    public static function formatContent($html = null)
    {
        if (empty($html)) {
            return '';
        }

        // 1. Remove empty paragraphs
        $html = preg_replace('/<p[^>]*>(?:\s|&nbsp;|<br\s*\/?>)*<\/p>/ui', '', $html);

        // 2. Add data-aos="fade-up" to block elements (p, h1-h6, ul, ol, blockquote, table) if not already present
        $html = preg_replace_callback('/<(p|h[1-6]|ul|ol|blockquote|table)([^>]*)>/iu', function ($matches) {
            $tag = $matches[1];
            $attrs = $matches[2];

            if (stripos($attrs, 'data-aos') === false) {
                return "<{$tag}{$attrs} data-aos=\"fade-up\">";
            }

            return "<{$tag}{$attrs}>";
        }, $html);

        // 3. Ensure loading="lazy" is added to images if not already present
        $html = preg_replace_callback('/<img\b([^>]*)>/iu', function ($matches) {
            $attrs = $matches[1];
            if (stripos($attrs, 'loading=') === false) {
                return '<img loading="lazy"' . $attrs . '>';
            }
            return "<img{$attrs}>";
        }, $html);

        return $html;
    }

    /**
     * Sanitize and format content in one step.
     *
     * @param string|null $html
     * @return string
     */
    public static function cleanAndFormat($html = null)
    {
        $formatted = self::formatContent($html);
        return self::clean($formatted);
    }
}
