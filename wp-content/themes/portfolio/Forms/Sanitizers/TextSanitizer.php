<?php

class TextSanitizer extends BaseSanitizer {
    public function getSanitizedValue()
    {
        // TODO: Implement getSanitizedValue() method.
        return sanitize_text_field($this->value);
    }
}