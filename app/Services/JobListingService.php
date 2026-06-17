<?php

namespace App\Services;

use App\Models\Job;
use App\Models\TechSpecialty;
use Illuminate\Http\Request;

class JobListingService
{
    public function validate(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'logo' => 'nullable|string|max:100',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|string|max:100',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0|gte:salary_min',
            'currency' => 'nullable|string|max:10',
            'remote_type' => 'required|string|max:50',
            'timezone' => 'nullable|string|max:50',
            'tech_specialty_id' => 'nullable|exists:tech_specialties,id',
            'description' => 'nullable|string',
            'responsibilities_text' => 'nullable|string',
            'requirements_text' => 'nullable|string',
            'benefits_text' => 'nullable|string',
            'skills_text' => 'nullable|string',
            'payment_methods_text' => 'nullable|string',
            'tags_text' => 'nullable|string',
            'tag_labels_json' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_syria_friendly' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان الوظيفة مطلوب',
            'company_name.required' => 'اسم الشركة مطلوب',
            'location.required' => 'الموقع مطلوب',
            'employment_type.required' => 'نوع الدوام مطلوب',
            'remote_type.required' => 'نوع العمل عن بُعد مطلوب',
            'logo_image.max' => 'حجم الشعار يجب أن يكون أقل من 2 ميجابايت',
            'salary_max.gte' => 'الحد الأعلى للراتب يجب أن يكون أكبر من أو يساوي الحد الأدنى',
        ]);
    }

    public function mergeFormArrays(Request $request, array $validated): array
    {
        $validated['responsibilities'] = $this->linesToArray($request->input('responsibilities_text'));
        $validated['requirements'] = $this->linesToArray($request->input('requirements_text'));
        $validated['benefits'] = $this->linesToArray($request->input('benefits_text'));
        $validated['skills'] = $this->csvToArray($request->input('skills_text'));
        $validated['payment_methods'] = $this->csvToArray($request->input('payment_methods_text'));
        $validated['tags'] = $this->csvToArray($request->input('tags_text'));

        $tagLabelsJson = trim((string) $request->input('tag_labels_json', ''));
        if ($tagLabelsJson !== '') {
            $decoded = json_decode($tagLabelsJson, true);
            $validated['tag_labels'] = is_array($decoded) ? $decoded : [];
        } else {
            $validated['tag_labels'] = $this->buildDefaultTagLabels($validated);
        }

        unset(
            $validated['responsibilities_text'],
            $validated['requirements_text'],
            $validated['benefits_text'],
            $validated['skills_text'],
            $validated['payment_methods_text'],
            $validated['tags_text'],
            $validated['tag_labels_json'],
            $validated['remove_logo_image']
        );

        return $validated;
    }

    public function buildDefaultTagLabels(array $data): array
    {
        $labels = [];

        if (($data['remote_type'] ?? '') === 'full-remote') {
            $labels[] = ['t' => 'عن بُعد 🌐', 'c' => 'teal'];
        } elseif (($data['remote_type'] ?? '') === 'hybrid') {
            $labels[] = ['t' => 'هجين', 'c' => 'blue'];
        }

        if (! empty($data['tech_specialty_id'])) {
            $specialty = TechSpecialty::find($data['tech_specialty_id']);
            if ($specialty) {
                $labels[] = ['t' => $specialty->name, 'c' => 'blue'];
            }
        }

        return $labels;
    }

    private function linesToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
    }

    private function csvToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
