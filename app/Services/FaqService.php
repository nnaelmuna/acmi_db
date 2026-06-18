<?php

namespace App\Services;

use App\Models\Faq;

class FaqService
{
    public function create(array $data)
    {
        return Faq::create($data);
    }

    public function getAll()
    {
        return Faq::all();
    }

    public function getById($id)
    {
        return Faq::find($id);
    }

    public function update($id, array $data)
    {
        $faq = $this->getById($id);

        if ($faq) {
            $faq->update($data);
            return $faq;
        }

        return null;
    }

    public function delete($id)
    {
        $faq = $this->getById($id);

        if ($faq) {
            $faq->delete();
            return true;
        }

        return false;
    }

    public function toggleStatus($id)
    {
        $faq = $this->getById($id);

        if ($faq) {
            $faq->status = $faq->status === 'published' ? 'draft' : 'published';
            $faq->save();
            return $faq;
        }

        return null;
    }
}