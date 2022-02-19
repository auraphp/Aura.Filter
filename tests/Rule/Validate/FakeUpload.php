<?php
namespace Aura\Filter\Rule\Validate;

class FakeUpload extends Upload
{
    public $is_uploaded_file = true;

    protected function isUploadedFile(string $file): bool
    {
        // hit the parent method ...
        parent::isUploadedFile($file);
        // ... but ignore its results.
        return $this->is_uploaded_file;
    }
}
