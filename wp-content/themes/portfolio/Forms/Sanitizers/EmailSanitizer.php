<?php

class EmailSanitizer extends BaseSanitizer {
    public function getSanitizedValue()
    {
        // TODO: Implement getSanitizedValue() method.
        return sanitize_email($this->value);
    }
}