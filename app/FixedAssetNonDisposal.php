<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAssetNonDisposal extends Model
{
    Use SoftDeletes;

    protected $fillable = ['disposal_request_date','form_number', 'fixed_asset_id', 'fixed_asset_name', 'clasification_id', 'category', 'section_control', 'reason', 'reason_jp', 'new_picture', 'registration_amount', 'registration_date', 'vendor', 'invoice_number', 'book_value', 'pic_incharge', 'mode', 'quotation_file', 'status', 'disposal_request_date', 'retired_at', 'remark', 'reject_status', 'comment', 'pic_app', 'pic_app_date', 'fa_app', 'fa_app_date', 'manager_app', 'manager_app_date', 'manager_acc_app', 'manager_acc_app_date', 'manager_disposal_app', 'manager_disposal_app_date', 'new_pic_app', 'new_pic_app_date','disposal_location', 'last_status', 'created_by'
    ];
}
