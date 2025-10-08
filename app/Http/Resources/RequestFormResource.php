<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->content,
            'form_data' => $this->form_data,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'submitted_at' => $this->submitted_at,
            'approved_at' => $this->approved_at,
            'rejected_at' => $this->rejected_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Employee info
            'employee' => [
                'id' => $this->employee->id ?? null,
                'name' => $this->employee->name ?? null,
                'email' => $this->employee->email ?? null,
                'phone' => $this->employee->phone ?? null,
            ],
        ];

        // ✅ THÊM TẤT CẢ SIGNATURE FIELDS CHO MỌI LOẠI ĐƠN
        if ($this->type === 'giay_uy_quyen') {
            // Giấy ủy quyền
            $data = array_merge($data, [
                'authorized_employee_id' => $this->authorized_employee_id,
                'digital_signature_delegator' => $this->digital_signature_delegator,
                'digital_signature_authorized' => $this->digital_signature_authorized,
                'delegator_approved_by' => $this->delegator_approved_by,
                'delegator_approved_at' => $this->delegator_approved_at,
                'authorized_approved_by' => $this->authorized_approved_by,
                'authorized_approved_at' => $this->authorized_approved_at,

                // ✅ Thêm flag check có chữ ký không
                'has_delegator_signature' => ! empty($this->digital_signature_delegator),
                'has_authorized_signature' => ! empty($this->digital_signature_authorized),

                // Authorized employee info
                'authorizedEmployee' => $this->whenLoaded('authorizedEmployee', function () {
                    return [
                        'id' => $this->authorizedEmployee->id,
                        'name' => $this->authorizedEmployee->name,
                        'email' => $this->authorizedEmployee->email,
                        'phone' => $this->authorizedEmployee->phone,
                    ];
                }),

                // Người đã ký - ✅ Full info theo yêu cầu FE
                'delegatorApprovedBy' => $this->whenLoaded('delegatorApprovedBy', function () {
                    return $this->delegatorApprovedBy ? [
                        'id' => $this->delegatorApprovedBy->id,
                        'name' => $this->delegatorApprovedBy->name,
                        'email' => $this->delegatorApprovedBy->email,
                        'phone' => $this->delegatorApprovedBy->phone,
                    ] : null;
                }),
                'authorizedApprovedBy' => $this->whenLoaded('authorizedApprovedBy', function () {
                    return $this->authorizedApprovedBy ? [
                        'id' => $this->authorizedApprovedBy->id,
                        'name' => $this->authorizedApprovedBy->name,
                        'email' => $this->authorizedApprovedBy->email,
                        'phone' => $this->authorizedApprovedBy->phone,
                    ] : null;
                }),
            ]);
        } else {
            // Đơn thường
            $data = array_merge($data, [
                'supervisor_id' => $this->supervisor_id,
                'digital_signature_applicant' => $this->digital_signature_applicant,
                'digital_signature_supervisor' => $this->digital_signature_supervisor,
                'digital_signature_manager' => $this->digital_signature_manager,
                'supervisor_approved_by' => $this->supervisor_approved_by,
                'supervisor_approved_at' => $this->supervisor_approved_at,
                'manager_approved_by' => $this->manager_approved_by,
                'manager_approved_at' => $this->manager_approved_at,

                // ✅ Thêm flag check có chữ ký không
                'has_applicant_signature' => ! empty($this->digital_signature_applicant),
                'has_supervisor_signature' => ! empty($this->digital_signature_supervisor),
                'has_manager_signature' => ! empty($this->digital_signature_manager),

                // Supervisor info
                'supervisor' => $this->whenLoaded('supervisor', function () {
                    return [
                        'id' => $this->supervisor->id,
                        'name' => $this->supervisor->name,
                        'email' => $this->supervisor->email,
                    ];
                }),

                // Người đã ký - ✅ Full info theo yêu cầu FE
                'supervisorApprovedBy' => $this->whenLoaded('supervisorApprovedBy', function () {
                    return $this->supervisorApprovedBy ? [
                        'id' => $this->supervisorApprovedBy->id,
                        'name' => $this->supervisorApprovedBy->name,
                        'email' => $this->supervisorApprovedBy->email,
                        'phone' => $this->supervisorApprovedBy->phone,
                    ] : null;
                }),
                'managerApprovedBy' => $this->whenLoaded('managerApprovedBy', function () {
                    return $this->managerApprovedBy ? [
                        'id' => $this->managerApprovedBy->id,
                        'name' => $this->managerApprovedBy->name,
                        'email' => $this->managerApprovedBy->email,
                        'phone' => $this->managerApprovedBy->phone,
                    ] : null;
                }),
            ]);
        }

        // Approved by info (cho tất cả loại đơn) - ✅ Full info theo yêu cầu FE
        $data['approvedBy'] = $this->whenLoaded('approvedBy', function () {
            return $this->approvedBy ? [
                'id' => $this->approvedBy->id,
                'name' => $this->approvedBy->name,
                'email' => $this->approvedBy->email,
                'phone' => $this->approvedBy->phone,
            ] : null;
        });

        return $data;
    }
}
