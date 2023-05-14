<?php

namespace App\Http\Controllers;

use App\GrgiLog;
use App\Mail\SendEmail;
use App\Material;
use App\MaterialControl;
use App\MaterialInOut;
use App\MaterialOver;
use App\MaterialOverReason;
use App\MaterialPlanDelivery;
use App\MaterialPlantDataList;
use App\MaterialRequirementPlan;
use App\MaterialStockPolicy;
use App\OriginGroup;
use App\ReturnAdditional;
use App\Smbmr;
use App\StorageLocation;
use App\User;
use App\VendorMail;
use App\WeeklyCalendar;
use Carbon\Carbon;
use DataTables;
use DateInterval;
use DatePeriod;
use DateTime;
use File;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Response;

class MaterialController extends Controller
{
    private $category;
    private $hpl;
    private $valcl;

    private $output = array();
    private $check = array();
    private $temp = array();

    public function __construct()
    {
        $this->middleware('auth');
        $this->hpl = [
            'ASBELL&BOW',
            'ASBODY',
            'ASFG',
            'ASKEY',
            'ASNECK',
            'ASPAD',
            'ASPART',
            'CASE',
            'CLBARREL',
            'CLBELL',
            'CLFG',
            'CLKEY',
            'CLLOWER',
            'CLPART',
            'CLUPPER',
            'FLBODY',
            'FLFG',
            'FLFOOT',
            'FLHEAD',
            'FLKEY',
            'FLPAD',
            'FLPART',
            'MOUTHPIECE',
            'PN',
            'PN PARTS',
            'RC',
            'TSBELL&BOW',
            'TSBODY',
            'TSFG',
            'TSKEY',
            'TSNECK',
            'TSPART',
            'VENOVA',
            'SX',
        ];
        $this->category = [
            'FG',
            'KD',
            'WIP',
            'RAW',
        ];
        $this->valcl = [
            '9010',
            '9030',
            '9040',
            '9041',
        ];
        $this->reason = [
            'Extra Order/Extra Usage',
            'Material NG',
            'Kesalahan Input Permintaan',
            'Lot Pengambilan Kanban',
            'Untuk Trial',
            'Substitusi/Switch Material',
            'Other Reason',
        ];

        // DEV
        // $this->token_type = 'ympicoid_api_login_trial';
        // $this->api_return_type = 'ympicoid_api_trial';
        // $this->api_json_type = 'ympicoid_api_trial_json';

        // LIVE
        $this->token_type = 'ympicoid_api_login';
        $this->api_return_type = 'ympicoid_api';
        $this->api_json_type = 'ympicoid_api_json';

    }

    public function editMaterialCheck(Request $request)
    {
        $remark = $request->get('remark');
        $material_number = $request->get('material_number');

        $material_control = db::table('material_controls')->where('material_number', '=', $material_number)->first();

        try {
            if ($remark == 'add') {
                db::table('material_controls')->where('material_number', '=', $material_number)
                    ->update([
                        'incoming' => 1,
                    ]);

                $response = array(
                    'status' => true,
                    'material_control' => $material_control,
                    'message' => 'Material berhasil ditambahkan ke pengecekan',
                );
                return Response::json($response);
            }

            if ($remark == 'rem') {
                db::table('material_controls')->where('material_number', '=', $material_number)
                    ->update([
                        'incoming' => null,
                    ]);

                $response = array(
                    'status' => true,
                    'material_control' => $material_control,
                    'message' => 'Material berhasil dikeluarkan dari pengecekan',
                );
                return Response::json($response);
            }
        } catch (\Exception$e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }

    }

    public function indexCheckMonitoring()
    {
        $title = "Monitoring Pengecekan Indirect Material";
        $title_jp = "";
        $material_controls = db::table('material_controls')->where('controlling_group', '=', 'INDIRECT')->get();

        return view('materials.check.monitoring', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'material_controls' => $material_controls,
        ))->with('head', 'Pengecekan Indirect Material');
    }

    public function indexCheckMaterial(Request $request)
    {
        $title = "Pengecekan Indirect Material";
        $title_jp = "";

        $material_check = db::connection('ympimis_2')->table('material_checks')
            ->where('inout_no', '=', $request->get('inout_no'))
            ->where('status', '=', 'Waiting')
            ->first();

        if (!$material_check) {
            return view('404');
        }

        return view('materials.check.check', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'material_check' => $material_check,
        ))->with('head', 'Pengecekan Indirect Material');
    }

    public function inputCheckMaterial(Request $request)
    {
        try {
            if ($request->input('quantity_total') > 0) {

                $update_check = db::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'status' => 'NG',
                        'ng_status' => 'NG Ditemukan',
                        'checked_by' => Auth::user()->username,
                        'checked_by_name' => Auth::user()->name,
                        'checked_at' => date('Y-m-d H:i:s'),
                    ]);

                $rows = $request->input('rows');
                foreach ($rows as $row) {
                    $filename = null;

                    if ($request->file('attachment_' . $row)) {
                        $file = $request->file('attachment_' . $row);
                        $filename = 'evd_' . $request->input('inout_no') . '.' . $request->input('extension_' . $row);
                        $file->move($file_destination, $filename);
                    }

                    $input_finding = db::connection('ympimis_2')->table('material_check_findings')
                        ->insert([
                            'inout_no' => $request->input('inout_no'),
                            'remark' => $request->input('remark_' . $row),
                            'qty' => $request->input('quantity_' . $row),
                            'evidence_file' => $filename,
                        ]);
                }

                $material_check = db::connection('ympmis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->first();

                $material_check_findings = db::connection('ympmis_2')->table('material_check_findings')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->get();

                $mail_to = array();
                $mail_cc = array();
                $approvers = db::table('approvers')->where('department_shortname_2', '=', $material_check->location)
                    ->whereIn('remark', ['Manager', 'Foreman', 'Chief', 'General Manager', 'Deputy General Manager'])
                    ->get();

                foreach ($approvers as $approver) {
                    if ($approver->remark == 'Foreman') {
                        if (!in_array($approver->approver_email, $mail_to)) {
                            array_push($mail_to, $approver->approver_email);
                        }
                    } else {
                        if (!in_array($approver->approver_email, $mail_cc)) {
                            array_push($mail_cc, $approver->approver_email);
                        }
                    }

                    if ($approver->remark == 'Manager') {
                        $manager_id = $approver->approver_id;
                        $manager_name = $approver->approver_name;
                        $manager_email = $approver->approver_email;
                    }
                }

                $input_detail_foreman = db::connection('ympimis_2')->table('material_check_details')
                    ->insert([
                        'inout_no' => $request->input('inout_no'),
                        'employee_id' => 'Foreman',
                        'employee_name' => 'Foreman',
                        'employee_email' => 'Foreman',
                        'position' => 'Foreman',
                        'status' => 'Waiting',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $input_detail_manager = db::connection('ympimis_2')->table('material_check_details')
                    ->insert([
                        'inout_no' => $request->input('inout_no'),
                        'employee_id' => $manager_id,
                        'employee_name' => $manager_name,
                        'employee_email' => $manager_email,
                        'position' => 'Manager',
                        'status' => 'Waiting',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $buyer = db::table('material_controls')->where('material_number', '=', $material_check->material_number)
                    ->leftJoin('users', 'users.username', '=', 'material_controls.pic')
                    ->first();

                $input_detail_buyer = db::connection('ympimis_2')->table('material_check_details')
                    ->insert([
                        'inout_no' => $request->input('inout_no'),
                        'employee_id' => $buyer->username,
                        'employee_name' => $buyer->name,
                        'employee_email' => $buyer->email,
                        'position' => 'Buyer',
                        'status' => 'Waiting',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $input_detail_vendor = db::connection('ympimis_2')->table('material_check_details')
                    ->insert([
                        'inout_no' => $request->input('inout_no'),
                        'employee_id' => $material_check->vendor_code,
                        'employee_name' => $material_check->vendor_name,
                        'employee_email' => $material_check->vendor_name,
                        'position' => 'Vendor',
                        'status' => 'Waiting',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $data = [
                    'title' => 'NG Material Ditemukan',
                    'material_check' => $material_check,
                    'material_check_findings' => $material_check_findings,
                ];

                array_push($mail_to, $buyer->email);
                array_push($mail_cc, 'imron.faisal@music.yamaha.com');
                array_push($mail_cc, 'adianto.heru@music.yamaha.com');

                Mail::to($mail_to)
                    ->cc($mail_cc)
                    ->bcc(['ympi-mis-ML@music.yamaha.com'])
                    ->send(new SendEmail($data, 'material_check_finding'));

                $response = array(
                    'status' => true,
                    'message' => 'Pengecekan berhasil dilakukan (Material NG)',
                );
                return Response::json($response);
            } else {
                $update_check = db::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'status' => 'OK',
                        'checked_by' => Auth::user()->username,
                        'checked_by_name' => Auth::user()->name,
                        'checked_at' => date('Y-m-d H:i:s'),
                    ]);

                $check = db::connection('ympimis_2')
                    ->select("INSERT INTO material_check_logs SELECT
                         *
                         FROM
                         material_checks
                         WHERE
                         inout_no = '" . $request->input('inout_no') . "'");

                $delete_check = db::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->delete();

                $response = array(
                    'status' => true,
                    'message' => 'Pengecekan berhasil dilakukan (Material OK)',
                );
                return Response::json($response);
            }
        } catch (\Exception$e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }

    public function inputCheckReport(Request $request)
    {
        try {
            $material_check = db::connection('ympimis_2')->table('material_checks')
                ->where('inout_no', '=', $request->input('inout_no'))
                ->first();
            $material_check_findings = db::connection('ympimis_2')->table('material_check_findings')
                ->where('inout_no', '=', $request->input('inout_no'))
                ->get();

            $mail_cc = [];

            $buyer = db::connection('ympimis_2')->table('material_check_details')
                ->where('inout_no', '=', $request->input('inout_no'))
                ->where('position', '=', 'Buyer')
                ->first();

            $foreman = db::connection('ympimis_2')->table('material_check_details')
                ->where('inout_no', '=', $request->input('inout_no'))
                ->where('position', '=', 'Foreman')
                ->first();

            $mail_approvers = db::select("SELECT
                         *
                    FROM
                    approvers
                    WHERE
                    remark IN (
                         'Chief',
                         'Foreman',
                         'Manager',
                         'Deputy General Manager',
                         'General Manager' )
                    AND department_shortname_2 IN (
                         'Procurement',
                         'Purchasing Control',
                         '" . $material_check->location . "')");

            foreach ($mail_approvers as $mail_approver) {
                if (!in_array($mail_approver->approver_email, $mail_cc)) {
                    array_push($mail_cc, $mail_approver->approver_email);
                }
            }

            if ($request->input('position') == 'Foreman') {

                $approvers = db::table('approvers')->where('remark', '=', 'Foreman')
                    ->where('department_shortname_2', '=', $material_check->location)
                    ->get();

                $approver = false;

                foreach ($approvers as $row) {
                    if ($row->approver_id == Auth::user()->username) {
                        $approver = true;
                    }
                }

                if (str_contains(Auth::user()->role_code, 'MIS')) {
                    $approver = true;
                }

                if ($approver == false) {
                    $response = array(
                        'status' => true,
                        'message' => 'Anda tidak memiliki "OTORITAS" untuk membuat laporan.',
                    );
                    return Response::json($response);
                }

                DB::connection('ympimis_2')->table('material_check_details')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->where('position', '=', $request->input('position'))
                    ->update([
                        'employee_id' => Auth::user()->username,
                        'employee_name' => Auth::user()->name,
                        'employee_email' => Auth::user()->email,
                        'status' => $request->input('status'),
                        'report' => $request->input('report'),
                        'confirmed_at' => date('Y-m-d H:i:s'),
                    ]);

                DB::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'ng_status' => $request->input('status'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $foreman = db::connection('ympimis_2')->table('material_check_details')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->where('position', '=', 'Foreman')
                    ->first();

                $data = [
                    'title' => 'Ketidaksesuaian Material Ditemukan',
                    'material_check' => $material_check,
                    'material_check_findings' => $material_check_findings,
                    'foreman' => $foreman,
                    'buyer' => [],
                    'position' => $request->input('position'),
                ];

                Mail::to($buyer->employee_email)
                    ->cc($mail_cc)
                    ->bcc(['ympi-mis-ML@music.yamaha.com'])
                    ->queue(new SendEmail($data, 'material_check_report'));

                $response = array(
                    'status' => true,
                    'message' => 'Laporan berhasil dibuat dan dikirimkan.',
                );
                return Response::json($response);
            }

            if ($request->input('position') == 'Buyer') {
                if (!str_contains(Auth::user()->role_code, 'MIS')) {
                    if (Auth::user()->username != $buyer->employee_id) {
                        $response = array(
                            'status' => true,
                            'message' => 'Anda tidak memiliki "OTORITAS" untuk membuat laporan.',
                        );
                        return Response::json($response);
                    }
                }

                $file_destination = 'files/material_check';
                $invoice_filename = null;
                $evidence_filename = null;

                if ($request->file('invoice')) {
                    $file = $request->file('invoice');
                    $invoice_filename = 'ng_inv_' . $request->input('inout_no') . '.' . $request->input('invoice_extension');
                    $file->move($file_destination, $invoice_filename);
                }
                if ($request->file('evidence')) {
                    $file = $request->file('evidence');
                    $evidence_filename = 'ng_evd_' . $request->input('inout_no') . '.' . $request->input('evidence_extension');
                    $file->move($file_destination, $evidence_filename);
                }

                DB::connection('ympimis_2')->table('material_check_details')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->where('position', '=', $request->input('position'))
                    ->update([
                        'status' => $request->input('status'),
                        'report' => $request->input('report'),
                        'confirmed_at' => date('Y-m-d H:i:s'),
                    ]);

                DB::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'ng_status' => $request->input('status'),
                        'ng_invoice_file' => $invoice_filename,
                        'ng_evidence_file' => $evidence_filename,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $data = [
                    'title' => 'Informasi NG Terikirim ke Vendor',
                    'material_check' => $material_check,
                    'material_check_findings' => $material_check_findings,
                    'foreman' => $foreman,
                    'buyer' => $buyer,
                    'position' => $request->input('position'),
                    'evidence' => $evidence_filename,
                    'invoice' => $invoice_filename,
                ];

                Mail::to($buyer->employee_email)
                    ->cc($mail_cc)
                    ->bcc(['ympi-mis-ML@music.yamaha.com'])
                    ->send(new SendEmail($data, 'material_check_report'));

                $response = array(
                    'status' => true,
                    'message' => 'Konfirmasi berhasil dibuat.',
                );
                return Response::json($response);
            }

            if ($request->input('position') == 'Vendor') {
                if (!str_contains(Auth::user()->role_code, 'MIS')) {
                    if (Auth::user()->username != $buyer->employee_id) {
                        $response = array(
                            'status' => true,
                            'message' => 'Anda tidak memiliki "OTORITAS" untuk membuat laporan.',
                        );
                        return Response::json($response);
                    }
                }

                $file_destination = 'files/material_check';
                $invoice_filename = null;

                if ($request->file('invoice')) {
                    $file = $request->file('invoice');
                    $invoice_filename = 'ok_inv_' . $request->input('inout_no') . '.' . $request->input('invoice_extension');
                    $file->move($file_destination, $invoice_filename);
                }

                DB::connection('ympimis_2')->table('material_check_details')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->where('position', '=', $request->input('position'))
                    ->update([
                        'status' => $request->input('status'),
                        'confirmed_at' => date('Y-m-d H:i:s'),
                    ]);

                DB::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'ng_status' => $request->input('status'),
                        'ok_invoice_file' => $invoice_filename,
                        'eta_date' => $request->input('eta'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $data = [
                    'title' => 'Rencana Kedatangan Material Pengganti dari Vendor',
                    'material_check' => $material_check,
                    'material_check_findings' => $material_check_findings,
                    'foreman' => $foreman,
                    'buyer' => $buyer,
                    'eta_date' => $request->input('eta'),
                    'position' => $request->input('position'),
                    'invoice' => $invoice_filename,
                ];

                Mail::to($buyer->employee_email)
                    ->cc($mail_cc)
                    ->bcc(['ympi-mis-ML@music.yamaha.com'])
                    ->send(new SendEmail($data, 'material_check_report'));

                $response = array(
                    'status' => true,
                    'message' => 'Informasi kedatangan material pengganti dikonfirmasi.',
                );
                return Response::json($response);
            }

            if ($request->input('position') == 'Arrived') {
                if (!str_contains(Auth::user()->role_code, 'MIS')) {
                    if (Auth::user()->username != $buyer->employee_id) {
                        $response = array(
                            'status' => true,
                            'message' => 'Anda tidak memiliki "OTORITAS" untuk membuat laporan.',
                        );
                        return Response::json($response);
                    }
                }

                DB::connection('ympimis_2')->table('material_checks')
                    ->where('inout_no', '=', $request->input('inout_no'))
                    ->update([
                        'ng_status' => $request->input('status'),
                        'arrived_date' => $request->input('arrived_date'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                $data = [
                    'title' => 'Material Pengganti Tiba di YMPI',
                    'material_check' => $material_check,
                    'material_check_findings' => $material_check_findings,
                    'foreman' => $foreman,
                    'buyer' => $buyer,
                    'arrived_date' => $request->input('arrived_date'),
                    'position' => $request->input('position'),
                ];

                Mail::to($buyer->employee_email)
                    ->cc($mail_cc)
                    ->bcc(['ympi-mis-ML@music.yamaha.com'])
                    ->send(new SendEmail($data, 'material_check_report'));

                $response = array(
                    'status' => true,
                    'message' => 'Informasi kedatangan material pengganti berhasil.',
                );
                return Response::json($response);
            }

        } catch (\Exception$e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }

    public function fetchCheckMaterial(Request $request)
    {
        $material_check_details = db::connection('ympimis_2')->table('material_check_details')->get();
        $material_check_findings = db::connection('ympimis_2')->table('material_check_findings')->get();

        $material_checks = db::connection('ympimis_2')->select("SELECT
                    TIMESTAMPDIFF(
                         DAY,
                         material_checks.posting_date,
                         now()) AS overdue,
                    DATE_FORMAT(material_checks.posting_date, '%Y-%m-01') AS period,
                    material_checks.*
                    FROM
                    material_checks ORDER BY material_checks.posting_date ASC");

        $material_check_logs = db::connection('ympimis_2')->select("SELECT
                    TIMESTAMPDIFF(
                         DAY,
                         material_check_logs.posting_date,
                         now()) AS overdue,
                    material_check_logs.*
                    FROM
                    material_check_logs
                    ORDER BY material_check_logs.posting_date DESC");

        $material_numbers = array();
        $periods = array();

        foreach ($material_checks as $material_check) {
            array_push($material_numbers, $material_check->material_number);
            array_push($periods, $material_check->period);
        }

        $inventories = db::connection('ymes')->table('vd_mes0040')
            ->where('stock_qty', '>', 0)
            ->where('location_code', '=', 'MSTK')
            ->whereIn('item_code', $material_numbers)
            ->get();

        $stock_policies = db::table('material_stock_policies')
            ->whereIn('period', $periods)
            ->whereIn('material_number', $material_numbers)
            ->get();

        $datas = array();

        foreach ($inventories as $inventory) {
            $key = '';
            $key .= ($inventory->item_code . '#');

            if (!array_key_exists($key, $datas)) {
                $data['item_code'] = $inventory->item_code;
                $data['quantity_stock'] = $inventory->stock_qty;
                $data['count_label'] = 1;
                $datas[$key] = $data;
            } else {
                $datas[$key]['quantity_stock'] = $datas[$key]['quantity_stock'] + $inventory->stock_qty;
                $datas[$key]['count_label'] = $datas[$key]['count_label'] + 1;
            }
        }

        $response = array(
            'status' => true,
            'material_checks' => $material_checks,
            'material_check_logs' => $material_check_logs,
            'material_check_details' => $material_check_details,
            'material_check_findings' => $material_check_findings,
            'stock_policies' => $stock_policies,
            'inventories' => $datas,
        );
        return Response::json($response);
    }

    public function index()
    {
        $origin_groups = OriginGroup::orderBy('origin_group_code', 'ASC')->get();

        return view('materials.index', array(
            'valcls' => $this->valcl,
            'hpls' => $this->hpl,
            'categories' => $this->category,
            'origin_groups' => $origin_groups,
        ))->with('page', 'Material');
    }

    public function indexVendor()
    {
        return view('materials.vendor')
            ->with('head', 'Raw Material')
            ->with('head2', 'Master Data')
            ->with('page', 'Vendor');
    }

    public function indexStockPolicy()
    {
        return view('materials.stock_policy')
            ->with('head', 'Raw Material')
            ->with('head2', 'Master Data')
            ->with('page', 'Stock Policy');
    }

    public function indexMaterialList()
    {
        return view('materials.material_list')
            ->with('head', 'Raw Material')
            ->with('head2', 'Master Data')
            ->with('page', 'Material List');
    }

    public function indexInOut()
    {
        $title = 'Material In Out';
        $title_jp = '';

        $vendor = MaterialControl::distinct()
            ->select('vendor_code', 'vendor_name')
            ->orderBy('vendor_name', 'ASC')
            ->get();

        $control = MaterialControl::leftJoin('employee_syncs', 'employee_syncs.employee_id', '=', 'material_controls.control')
            ->select('material_controls.control', 'employee_syncs.name')
            ->distinct()
            ->orderBy('employee_syncs.name', 'ASC')
            ->get();

        return view('materials.in_out', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'vendors' => $vendor,
            'controls' => $control,
        ))->with('page', $title)->with('Head', $title);
    }

    public function indexDeliveryMonitoring()
    {

        $title = 'Plan Delivery Monitoring';
        $title_jp = '';

        return view('materials.control_delivery_monitoring', array(
            'title' => $title,
            'title_jp' => $title_jp,
        ))->with('page', $title)->with('Head', $title);

    }

    public function fetchDeliveryMonitoring(Request $request)
    {

        $month = $request->get('month');
        $first = $month . '-01';
        $end = date("Y-m-t", strtotime($first));

        if (strlen($month) <= 0) {
            $month = date('Y-m');
            $first = date('Y-m') . '-01';
            $end = date("Y-m-t", strtotime($first));
        }

        $calendar = WeeklyCalendar::where('week_date', 'LIKE', '%' . $month . '%')
            ->select(
                'weekly_calendars.*',
                db::raw('DATE_FORMAT(week_date,"%d-%b") AS date_name')
            )
            ->orderBy('week_date')
            ->get();

        $plan = db::connection('ymes_rio')
            ->select("SELECT po_no, po_sub_no, delivery_date, item_code, odr_qty FROM vd_sap0050 AS po
                    WHERE po.delivery_date >= '" . $first . "'
                    AND po.delivery_date <= '" . $end . "'
                    ORDER BY po_no, po_sub_no, delivery_date, item_code");

        $actual_resume = db::connection('ymes_rio')
            ->select("SELECT delivery.po_no, delivery.po_sub_no, delivery.item_code, MAX(delivery.post_date) AS post_date, SUM(delivery.qty) AS qty FROM vd_sap0120_010 AS delivery
                     WHERE delivery.post_date >= '" . $first . "'
                    AND delivery.post_date <= '" . $end . "'
                    AND LENGTH(delivery.item_code) > 0
                    GROUP BY delivery.po_no, delivery.po_sub_no, delivery.item_code");

        $actual = db::connection('ymes_rio')
            ->select("SELECT delivery.po_no, delivery.po_sub_no, delivery.item_code, delivery.post_date, delivery.qty FROM vd_sap0120_010 AS delivery
                    WHERE delivery.post_date >= '" . $first . "'
                    AND delivery.post_date <= '" . $end . "'
                    AND LENGTH(delivery.item_code) > 0
                    ORDER BY delivery.po_no, delivery.po_sub_no, delivery.item_code, delivery.post_date");

        $response = array(
            'status' => true,
            'month_name' => date('F Y', strtotime($first)),
            'calendar' => $calendar,
            'plan' => $plan,
            'actual_resume' => $actual_resume,
            'actual' => $actual,
            'now' => date('Y-m-d'),
        );
        return Response::json($response);

    }

    public function indexControlDelivery()
    {
        $title = 'Delivery Control Monitoring';
        $title_jp = '納期管理の監視';

        $vendor = MaterialControl::distinct()
            ->select('vendor_code', 'vendor_name')
            ->orderBy('vendor_name', 'ASC')
            ->get();

        $control = MaterialControl::leftJoin('employee_syncs', 'employee_syncs.employee_id', '=', 'material_controls.control')
            ->select('material_controls.control', 'employee_syncs.name')
            ->distinct()
            ->orderBy('employee_syncs.name', 'ASC')
            ->get();

        return view('materials.control_delivery', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'vendors' => $vendor,
            'controls' => $control,
        ))->with('page', $title)->with('Head', $title);
    }

    public function indexProductionPlan()
    {
        $title = 'Production Plan';
        $title_jp = '生産計画';

        return view('materials.production_plan', array(
            'title' => $title,
            'title_jp' => $title_jp,
        ))->with('page', $title)->with('Head', $title);
    }

    public function indexForecastUsage()
    {
        $title = 'Forecast Usage';
        $title_jp = '見込み使用量';

        return view('materials.forecast_usage', array(
            'title' => $title,
            'title_jp' => $title_jp,
        ))->with('page', $title)->with('Head', $title);
    }

    public function indexMrp($category)
    {
        if ($category == 'indirect') {
            $title = 'MRP Indirect Material';
            $title_jp = '間材MRP';

            return view('materials.mrp', array(
                'title' => $title,
                'title_jp' => $title_jp,
            ))->with('page', $title)->with('Head', $title);
        }
    }

    public function indexReportGrgi()
    {
        $title = 'GRGI Report';
        $title_jp = 'GRGIリポート';

        return view('materials.report_grgi', array(
            'title' => $title,
            'title_jp' => $title_jp,
        ))->with('page', $title)->with('Head', $title);
    }

    public function indexPlanUsage($id)
    {

        $vendor = db::select("SELECT DISTINCT vendor_code, vendor_name FROM `material_controls`
                    ORDER BY vendor_code");

        $buyer = db::select("SELECT * FROM employee_syncs
                    WHERE employee_id IN (SELECT DISTINCT pic FROM material_controls)");

        if ($id == 'monthly') {
            $title = 'Montly Plan Usage';
            $title_jp = '';

            return view('materials.plan_usage', array(
                'title' => $title,
                'title_jp' => $title_jp,
                'vendors' => $vendor,
                'buyers' => $buyer,
            ))->with('page', $title)->with('Head', $title);
        }

        if ($id == 'daily') {
            $title = 'Daily Plan Usage';
            $title_jp = '';

            return view('materials.daily_plan_usage', array(
                'title' => $title,
                'title_jp' => $title_jp,
                'vendors' => $vendor,
                'buyers' => $buyer,
            ))->with('page', $title)->with('Head', $title);
        }
    }

    public function indexSmbmr()
    {
        $title = 'SMBMR';
        $title_jp = '';

        $smbmr = Smbmr::get();

        return view('materials.smbmr', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'smbmr' => $smbmr,
        ))->with('page', 'SMBMR')->with('Head', 'SMBMR');
    }

    public function indexMaterialMonitoring($id)
    {

        if ($id == 'direct') {
            $title = 'Raw Material Monitoring (Direct)';
            $title_jp = '素材監視「直材」';
            $controlling_group = 'DIRECT';
        }

        if ($id == 'indirect') {
            $title = 'Raw Material Monitoring (Indirect)';
            $title_jp = '素材監視「間材」';
            $controlling_group = 'INDIRECT';
        }

        if ($id == 'subcont') {
            $title = 'Raw Material Monitoring (Subcont)';
            $title_jp = '素材監視「サブコン」';
            $controlling_group = 'SUBCONT';
        }

        $material = MaterialControl::where('controlling_group', $controlling_group)
            ->orderBy('material_number', 'ASC')
            ->get();

        $vendor = MaterialControl::where('controlling_group', $controlling_group)
            ->select('vendor_code', 'vendor_name')
            ->distinct()
            ->orderBy('vendor_name', 'ASC')
            ->get();

        return view('materials.material_monitoring', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'controlling_group' => $controlling_group,
            'materials' => $material,
            'vendors' => $vendor,
        ))->with('page', 'Raw Material Monitoring')->with('Head', 'Raw Material Monitoring');
    }

    public function indexReasonOverUsage($date, $material_number)
    {

        $title = 'Reason Raw Material Over Plan Usage';
        $title_jp = '';

        $data = MaterialOver::where('date', $date)
            ->where('material_number', $material_number)
            ->first();

        return view('materials.reason', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'reasons' => $this->reason,
            'data' => $data,
        ))->with('page', 'Reason Raw Material Over Plan Usage')->with('Head', 'Reason Raw Material Over Plan Usage');
    }

    public function saveReasonOverUsage(Request $request)
    {
        $id = $request->get('id');
        $reason = $request->get('reason');
        $detail = $request->get('detail');

        try {
            $reason = new MaterialOverReason([
                'material_over_id' => $id,
                'reason' => $reason,
                'detail' => $detail,
                'created_by' => Auth::user()->username,
            ]);
            $reason->save();

            $response = array(
                'status' => true,
                'message' => 'Reason berhasil disimpan',
            );
            return Response::json($response);
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);
        }
    }

    public function uploadReportGrgi(Request $request)
    {
        $month = $request->get('month') . '-01';
        $upload = $request->get('upload');
        $uploadRows = preg_split("/\r?\n/", $upload);

        DB::beginTransaction();
        $delete = GrgiLog::where('month', '=', $month)->forceDelete();

        foreach ($uploadRows as $uploadRow) {
            $uploadColumn = preg_split("/\t/", $uploadRow);

            $valcl = $uploadColumn[0];
            $location = $uploadColumn[1];
            $material_number = $uploadColumn[2];
            $receipt_quantity = $uploadColumn[3];
            $receipt_amount = $uploadColumn[4];
            $issue_quantity = $uploadColumn[5];
            $issue_amount = $uploadColumn[6];
            $ending_quantity = $uploadColumn[7];
            $ending_amount = $uploadColumn[8];

            try {
                $insert = new GrgiLog([
                    'month' => $month,
                    'valcl' => $valcl,
                    'location' => $location,
                    'material_number' => $material_number,
                    'receipt_quantity' => $receipt_quantity,
                    'receipt_amount' => $receipt_amount,
                    'issue_quantity' => $issue_quantity,
                    'issue_amount' => $issue_amount,
                    'ending_quantity' => $ending_quantity,
                    'ending_amount' => $ending_amount,
                    'created_by' => Auth::id(),
                ]);
                $insert->save();

            } catch (Exception $e) {
                DB::rollback();
                $response = array(
                    'status' => false,
                    'message' => $e->getMessage(),
                );
                return Response::json($response);
            }
        }

        DB::commit();
        $response = array(
            'status' => true,
        );
        return Response::json($response);
    }

    public function deleteMaterialMonitoring(Request $request)
    {
        $upload = $request->get('delete_data');
        $uploadRows = preg_split("/\r?\n/", $upload);

        $material_number = [];
        foreach ($uploadRows as $uploadRow) {
            if (!in_array($uploadRow, $material_number)) {
                array_push($material_number, $uploadRow);
            }
        }

        $lists = db::table('material_controls')
            ->whereIn('material_number', $material_number)
            ->get();

        try {

            $delete = db::table('material_controls')
                ->whereIn('material_number', $material_number)
                ->delete();

            $response = array(
                'status' => true,
                'message' => 'Data deleted succesfully',
                'count' => count($lists),
            );
            return Response::json($response);

        } catch (\Exception$e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);

        }

    }

    public function uploadMaterialMonitoring(Request $request)
    {
        $controller = app()->make('App\Http\Controllers\MiraiMobileController');
        $token = app()->call([$controller, $this->token_type], []);
        $if_data = [];

        $id = $request->get('id');
        $upload = $request->get('upload');
        $controlling_group = $request->get('controlling_group');
        $error_count = array();
        $ok_count = array();

        $uploadRows = preg_split("/\r?\n/", $upload);

        if ($id == 'policy') {
            $period = date('Y-m-01', strtotime($request->get('period')));
            $delete = MaterialStockPolicy::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_stock_policies.material_number')
                ->where('material_stock_policies.period', '=', $period)
                ->forceDelete();
        }

        if ($id == 'usage') {
            $period = date('Y-m-01', strtotime($request->get('period')));
            $delete = MaterialRequirementPlan::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_requirement_plans.material_number')
                ->where('material_requirement_plans.due_date', '=', $period)
                ->where('material_controls.controlling_group', '=', $controlling_group)
                ->forceDelete();
        }

        if ($id == 'delivery') {
            // $period_from = date('Y-m-01', strtotime($request->get('period')));
            // $period_to = date('Y-m-t', strtotime($request->get('period')));
            // $delete = MaterialPlanDelivery::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            //     ->where('material_plan_deliveries.due_date', '>=', $period_from)
            //     ->where('material_plan_deliveries.due_date', '<=', $period_to)
            //     ->where('material_controls.controlling_group', '=', $controlling_group)
            //     ->forceDelete();

            $array_po = array();
            foreach ($uploadRows as $uploadRow) {
                $uploadColumn = preg_split("/\t/", $uploadRow);
                $po_number = $uploadColumn[1];

                if (!in_array($po_number, $array_po)) {
                    array_push($array_po, $po_number);
                }
            }

            $exist_po = array();
            $deliveries = MaterialPlanDelivery::whereIn('po_number', $array_po)->get();
            foreach ($deliveries as $delivery) {
                if (!in_array($delivery->po_number, $exist_po)) {
                    array_push($exist_po, $delivery->po_number);
                }
            }
        }

        if ($id == 'update_delivery') {
            // foreach ($uploadRows as $uploadRow) {
            //     $uploadColumn = preg_split("/\t/", $uploadRow);

            //     $po_number = $uploadColumn[0];
            //     $item_line = $uploadColumn[1];
            //     $material = $uploadColumn[2];
            //     $due_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[3])->format('Y-m-d');

            //     try {
            //         $delete = MaterialPlanDelivery::where('po_number', $po_number)
            //             ->where('item_line', $item_line)
            //             ->where('material_number', $material)
            //             ->where('due_date', $due_date)
            //             ->forceDelete();
            //     } catch (Exception $e) {
            //         array_push($error_count, $e->getMessage());

            //     }
            // }
        }

        if ($id == 'inout') {
            // $period_from = date('Y-m-d', strtotime($request->get('inoutFrom')));
            // $period_to = date('Y-m-d', strtotime($request->get('inoutTo')));
            // $delete = MaterialInOut::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_in_outs.material_number')
            //     ->where('material_in_outs.entry_date', '>=', $period_from)
            //     ->where('material_in_outs.entry_date', '<=', $period_to)
            //     ->where('material_controls.controlling_group', '=', $controlling_group)
            //     ->forceDelete();
        }

        $row = 0;
        foreach ($uploadRows as $uploadRow) {
            $uploadColumn = preg_split("/\t/", $uploadRow);
            $row++;

            if ($id == 'indirect' || $id == 'non_indirect') {
                if ($id == 'non_indirect') {
                    $controlling_group = $uploadColumn[0];
                    $material = $uploadColumn[1];
                    $description = $uploadColumn[2];
                    $pgr = $uploadColumn[3];
                    $vendor_code = $uploadColumn[4];
                    $vendor_name = $uploadColumn[5];
                    $category = $uploadColumn[6];
                    $pic = $uploadColumn[7];
                    $control = $uploadColumn[8];
                    $lead_time = $uploadColumn[9];
                    $mpq = $uploadColumn[10];
                    $moq = $uploadColumn[11];
                    $material_category = $uploadColumn[12];
                    $remark = $uploadColumn[13];

                    if (strlen($material) != 7) {
                        array_push($error_count, $row . '_' . $controlling_group . ' GMC Unmatch ' . $material . ' (' . strlen($material) . ' character)');
                    } else if (strlen($vendor_code) != 6) {
                        array_push($error_count, $row . '_' . 'Vendor Code Unmatch [' . $vendor_code . '] (' . strlen($vendor_code) . ')');
                    } else if ($category != 'LOKAL' && $category != 'IMPORT') {
                        array_push($error_count, $row . '_' . 'Category Code Unmatch [' . $category . '] (' . strlen($category) . ')');
                    } else if ($material == "" || $description == "" || $vendor_code == "" || $vendor_name == "" || $category == "" || $pic == "" || $lead_time == "" || $mpq == "" || $moq == "" || $remark == "") {
                        array_push($error_count, $row . '_' . 'Data Blank ' . $material);
                    } else if (strlen($pic) != 9) {
                        array_push($error_count, $row . '_' . 'NIK Buyer Unmatch [' . $pic . '] (' . strlen($pic) . ')');
                    } else if (strlen($control) != 9) {
                        array_push($error_count, $row . '_' . 'NIK Control Unmatch [' . $control . '] (' . strlen($control) . ')');
                    } else {
                        try {
                            $material_control = MaterialControl::updateOrCreate(
                                [
                                    'material_number' => strtoupper($material),
                                ], [
                                    'material_description' => strtoupper($description),
                                    'purchasing_group' => strtoupper($pgr),
                                    'controlling_group' => strtoupper($controlling_group),
                                    'vendor_code' => strtoupper($vendor_code),
                                    'vendor_name' => strtoupper($vendor_name),
                                    'category' => strtoupper($category),
                                    'pic' => strtoupper($pic),
                                    'control' => strtoupper($control),
                                    'lead_time' => $lead_time,
                                    'multiple_order' => $mpq,
                                    'minimum_order' => $moq,
                                    'remark' => strtoupper($remark),
                                    'material_category' => strtoupper($material_category),
                                    'created_by' => Auth::id(),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]
                            );
                            $material_control->save();

                            array_push($ok_count, 'ok');
                        } catch (Exception $e) {
                            array_push($error_count, $row . '_' . $e->getMessage());
                        }

                    }

                } elseif ($id == 'indirect') {
                    $controlling_group = $uploadColumn[0];
                    $material = $uploadColumn[1];
                    $description = $uploadColumn[2];
                    $pgr = $uploadColumn[3];
                    $vendor_code = $uploadColumn[4];
                    $vendor_name = $uploadColumn[5];
                    $category = $uploadColumn[6];
                    $pic = $uploadColumn[7];
                    $control = $uploadColumn[8];
                    $lead_time = $uploadColumn[9];
                    $dts = $uploadColumn[10];
                    $mpq = $uploadColumn[11];
                    $moq = $uploadColumn[12];
                    $remark = $uploadColumn[13];
                    $material_category = $uploadColumn[14];

                    if (strlen($material) < 7 || strlen($material) > 9) {
                        array_push($error_count, $row . '_' . $controlling_group . ' GMC Unmatch ' . $material . ' (' . strlen($material) . ' character)');
                    } else if (strlen($vendor_code) != 6) {
                        array_push($error_count, $row . '_' . 'Vendor Code Unmatch [' . $vendor_code . '] (' . strlen($vendor_code) . ')');
                    } else if ($category != 'LOKAL' && $category != 'IMPORT') {
                        array_push($error_count, $row . '_' . 'Category Code Unmatch [' . $category . '] (' . strlen($category) . ')');
                    } else if ($material == "" || $description == "" || $vendor_code == "" || $vendor_name == "" || $category == "" || $pic == "" || $lead_time == "" || $dts == "" || $mpq == "" || $moq == "" || $remark == "") {
                        array_push($error_count, $row . '_' . 'Data Blank ' . $material);
                    } else if (strlen($pic) != 9) {
                        array_push($error_count, $row . '_' . 'NIK Buyer Unmatch [' . $pic . '] (' . strlen($pic) . ')');
                    } else if (strlen($control) != 9) {
                        array_push($error_count, $row . '_' . 'NIK Control Unmatch [' . $control . '] (' . strlen($control) . ')');
                    } else {
                        try {
                            $material_control = MaterialControl::updateOrCreate(
                                [
                                    'material_number' => strtoupper($material),
                                ], [
                                    'material_description' => strtoupper($description),
                                    'purchasing_group' => strtoupper($pgr),
                                    'controlling_group' => strtoupper($controlling_group),
                                    'vendor_code' => strtoupper($vendor_code),
                                    'vendor_name' => strtoupper($vendor_name),
                                    'category' => strtoupper($category),
                                    'pic' => strtoupper($pic),
                                    'control' => strtoupper($control),
                                    'lead_time' => $lead_time,
                                    'dts' => $dts,
                                    'multiple_order' => $mpq,
                                    'minimum_order' => $moq,
                                    'remark' => strtoupper($remark),
                                    'material_category' => strtoupper($material_category),
                                    'created_by' => Auth::id(),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]
                            );
                            $material_control->save();

                            array_push($ok_count, 'ok');
                        } catch (Exception $e) {
                            array_push($error_count, $row . '_' . $e->getMessage());
                        }

                    }

                }
            }

            if ($id == 'policy') {
                $material = $uploadColumn[0];
                $description = $uploadColumn[1];
                $day = $uploadColumn[2];
                $policy = $uploadColumn[3];

                $cek = MaterialControl::where('material_number', $material)->first();

                if ($cek) {
                    if (strlen($material) < 7 || strlen($material) > 9) {
                        array_push($error_count, $row . '_' . $material . ' is invalid GMC');
                    } else if ($period == "" || $material == "" || $description == "" || $policy == "") {
                        array_push($error_count, $row . '_Data Blank ' . $material);
                    } else if (preg_match("/[a-z]/i", $day)) {
                        array_push($error_count, $row . '_Policy data not number');
                    } else if (preg_match("/[a-z]/i", $policy)) {
                        array_push($error_count, $row . '_Policy data not number');
                    } else {
                        try {
                            $material_stock_policy = new MaterialStockPolicy([
                                'period' => $period,
                                'material_number' => $material,
                                'material_description' => $description,
                                'day' => $day,
                                'policy' => $policy,
                                'created_by' => Auth::id(),
                            ]);
                            $material_stock_policy->save();

                            array_push($ok_count, 'ok');
                        } catch (QueryException $e) {
                            array_push($error_count, $row . '_' . substr($e->getMessage(), 0, 15));
                        }
                    }
                } else {
                    array_push($error_count, $row . '_' . $material . ' not found in monitored material');
                }
            }

            if ($id == 'usage') {
                $material = $uploadColumn[0];
                $due_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[1])->format('Y-m-d');
                $usage = $uploadColumn[2];
                $remark = "";

                $cek = MaterialControl::where('material_number', $material)->first();

                if ($cek) {
                    if (strlen($material) != 7 && ($controlling_group == 'DIRECT' || $controlling_group == 'SUBCONT')) {
                        array_push($error_count, 'GMC Unmatch ' . $material . ' (' . strlen($material) . ')');
                    } else if ((strlen($material) < 7 || strlen($material) > 9) && $controlling_group == 'INDIRECT') {
                        array_push($error_count, 'GMC Unmatch ' . $material . ' (' . strlen($material) . ')');
                    } else if ($due_date == "" || $material == "" || $usage == "") {
                        array_push($error_count, 'Data Blank ' . $material);
                    } else if (date('Y-m', strtotime($due_date)) != date('Y-m', strtotime($request->get('period')))) {
                        array_push($error_count, 'Period Unmatch ' . $material . ' ' . date('Y-m', strtotime($due_date)) . ' ' . date('Y-m', strtotime($request->get('period'))));
                    } else if (preg_match("/[a-z]/i", $usage)) {
                        array_push($error_count, 'Data not number ' . $material . ' ' . $due_date . ' ' . $usage);
                    } else if ($cek->controlling_group != $controlling_group) {
                        array_push($error_count, 'Upload menu for ' . $controlling_group . ' but material is ' . $cek->controlling_group);
                    } else {
                        try {
                            $material_requirement_plan = new MaterialRequirementPlan([
                                'material_number' => $material,
                                'due_date' => $due_date,
                                'usage' => $usage,
                                'remark' => $remark,
                                'created_by' => Auth::id(),
                            ]);
                            $material_requirement_plan->save();

                            array_push($ok_count, 'ok');
                        } catch (Exception $e) {
                            array_push($error_count, $e->getMessage());
                        }
                    }
                } else {
                    array_push($error_count, $material . ' not found in monitored material');
                }
            }

            if ($id == 'delivery') {
                $issue_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[0])->format('Y-m-d');
                $po_number = $uploadColumn[1];
                $item_line = $uploadColumn[2];
                $vendor_code = $uploadColumn[3];
                $material = $uploadColumn[4];
                $due_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[5])->format('Y-m-d');
                $quantity = $uploadColumn[6];

                if (!in_array($po_number, $exist_po)) {
                    $cek = MaterialControl::where('material_number', $material)->first();

                    if ($cek) {
                        if ($issue_date == "" || $po_number == "" || $item_line == "" || $due_date == "" || $quantity == "") {
                            array_push($error_count, $row . '_' . 'Data Blank ' . $material);
                        } else if (strlen($vendor_code) != 6) {
                            array_push($error_count, $row . '_' . 'Vendor code not match ' . $vendor_code);
                        } else if (preg_match("/[a-z]/i", $quantity)) {
                            array_push($error_count, $row . '_' . 'Data not number ' . $material . ' ' . $due_date . ' ' . $quantity);
                        } else {
                            try {
                                $material_plan_delivery = new MaterialPlanDelivery([
                                    'po_number' => $po_number,
                                    'item_line' => $item_line,
                                    'material_number' => $material,
                                    'issue_date' => $issue_date,
                                    'eta_date' => $due_date,
                                    'due_date' => $due_date,
                                    'quantity' => $quantity,
                                    'vendor_code' => $vendor_code,
                                    'created_by' => Auth::id(),
                                ]);
                                $material_plan_delivery->save();

                                $insert[] = $material_plan_delivery;
                                // PECAH IF YMPI CO ID
                                if (($row % 50 == 0 || $row == (count($uploadRows))) && $row != 0) {
                                    array_push($if_data, $insert);
                                    $insert = [];
                                }

                                array_push($ok_count, $row . '_' . 'ok');

                            } catch (Exception $e) {
                                array_push($error_count, $row . '_' . $e->getMessage());
                            }
                        }
                    } else {
                        array_push($error_count, $row . '_' . $material . ' not found in monitored material');
                    }
                } else {
                    array_push($error_count, $row . '_' . 'PO ' . $po_number . ' already exist');
                }
            }

            $update_id = [];
            if ($id == 'update_delivery') {
                $po_number = $uploadColumn[0];
                $item_line = $uploadColumn[1];
                $vendor_code = $uploadColumn[2];
                $material = $uploadColumn[3];
                $due_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[4])->format('Y-m-d');
                $rev_due_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[5])->format('Y-m-d');
                $rev_quantity = $uploadColumn[6];

                $cek = MaterialPlanDelivery::where('po_number', $po_number)
                    ->where('item_line', $item_line)
                    ->where('vendor_code', $vendor_code)
                    ->where('material_number', $material)
                    ->first();

                if ($cek) {
                    if ($cek->po_confirm != 1) {
                        array_push($error_count, $row . '_' . 'PO not yet confirmed');
                    } else if ($po_number == "" || $item_line == "" || $vendor_code == "" || $material == "" || $due_date == "" || $rev_due_date == "" || $rev_quantity == "") {
                        array_push($error_count, $row . '_' . 'Data Blank');
                    } else if (preg_match("/[a-z]/i", $rev_quantity)) {
                        array_push($error_count, $row . '_' . 'Data not number ' . $material . ' ' . $due_date . ' ' . $rev_quantity);
                    } else {

                        array_push($update_id, $cek->id);
                        try {
                            $material_plan_delivery = new MaterialPlanDelivery([
                                'po_number' => $cek->po_number,
                                'item_line' => $cek->item_line,
                                'material_number' => $cek->material_number,
                                'vendor_code' => $cek->vendor_code,
                                'po_send' => $cek->po_send,
                                'po_send_at' => $cek->po_send_at,
                                'po_confirm' => $cek->po_confirm,
                                'po_confirm_at' => $cek->po_confirm_at,
                                'po_reminder_at' => $cek->po_reminder_at,
                                'send_reminder_confirm_at' => $cek->send_reminder_confirm_at,
                                'reminder_confirm_at' => $cek->reminder_confirm_at,
                                'issue_date' => $cek->issue_date,
                                'eta_date' => $cek->eta_date,
                                'due_date' => $rev_due_date,
                                'quantity' => $rev_quantity,
                                'plan' => $cek->plan,
                                'actual' => $cek->actual,
                                'note' => $cek->note,
                                'status' => $cek->status,
                                'do_number' => $cek->do_number,
                                'invoice' => $cek->invoice,
                                'receive_report' => $cek->receive_report,
                                'bc_document' => $cek->bc_document,
                                'sppb' => $cek->sppb,
                                'bc_send_at' => $cek->bc_send_at,
                                'created_by' => Auth::id(),
                                'deleted_at' => $cek->deleted_at,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),

                            ]);
                            $material_plan_delivery->save();

                            array_push($ok_count, $row . '_' . 'ok');
                        } catch (Exception $e) {
                            array_push($error_count, $row . '_' . $e->getMessage());
                        }
                    }
                } else {
                    array_push($error_count, $row . '_' . 'Plan delivery not found');
                }
            }

            if ($id == 'inout') {
                $entry_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[0])->format('Y-m-d');
                $posting_date = Carbon::createFromFormat('d/m/Y', $uploadColumn[1])->format('Y-m-d');
                $po_number = $uploadColumn[2];
                $item_line = $uploadColumn[3];
                $bc = $uploadColumn[4];
                $material = $uploadColumn[5];
                $movement_type = $uploadColumn[6];
                $issue_location = $uploadColumn[7];
                $receive_location = $uploadColumn[8];
                $quantity = $uploadColumn[9];

                $cek = MaterialControl::where('material_number', $material)->first();

                if ($cek) {
                    if (preg_match("/[a-z]/i", $quantity)) {
                        array_push($error_count, $row . '_Data not number ' . $material . ' ' . $posting_date . ' ' . $quantity);
                    } else {
                        if ($movement_type == '101' || $movement_type == '102') {
                            $receive_location = null;
                        }
                        try {
                            if (strlen($po_number) <= 0) {
                                $po_number = '';
                            }
                            if (strlen($item_line) <= 0) {
                                $item_line = 0;
                            }
                            if (strlen($bc) <= 0) {
                                $bc = '';
                            }
                            $material_in_out = new MaterialInOut([
                                'po_number' => $po_number,
                                'item_line' => $item_line,
                                'material_number' => $material,
                                'movement_type' => $movement_type,
                                'issue_location' => $issue_location,
                                'receive_location' => $receive_location,
                                'quantity' => $quantity,
                                'bc_document' => $bc,
                                'entry_date' => $entry_date,
                                'posting_date' => $posting_date,
                                'created_by' => Auth::id(),
                            ]);
                            $material_in_out->save();

                            if (is_null($receive_location)) {
                                $delivery = MaterialPlanDelivery::where('po_number', $po_number)
                                    ->where('item_line', $item_line)
                                    ->where('due_date', $entry_date)
                                    ->where('material_number', $material)
                                    ->first();

                                if ($delivery) {
                                    $delivery->actual = $delivery->actual + $quantity;
                                    $delivery->bc_document = $bc;
                                    $delivery->save();
                                }
                            }

                            array_push($ok_count, $row . '_ok');
                        } catch (Exception $e) {
                            array_push($error_count, $e->getMessage());
                        }
                    }
                } else {
                    array_push($error_count, $row . '_' . $material . ' not found in monitored material');
                }
            }

        }

        for ($i = 0; $i < count($if_data); $i++) {
            $param = json_encode($if_data[$i]);
            $link = 'insert/plan_delivery';
            $method = 'POST';
            $controller = app()->make('App\Http\Controllers\MiraiMobileController');

            $insert = app()->call([$controller, $this->api_json_type], [
                'token' => $token,
                'link' => $link,
                'method' => $method,
                'param' => $param,
            ]);

        }

        if (count($update_id) > 0) {
            $delete = MaterialPlanDelivery::whereIn('id', $update_id)
                ->forceDelete();

        }

        $response = array(
            'status' => true,
            'id' => $id,
            'error_count' => $error_count,
            'ok_count' => $ok_count,
            'message' => 'ERROR: ' . count($error_count) . ' OK: ' . count($ok_count),
        );
        return Response::json($response);

    }

    public function fetchVendor()
    {

        $vendor = db::select("SELECT base_mail.*, cc.cc FROM
                    (SELECT vendor_mails.vendor_code, control.vendor_name, vendor_mails.`name`, vendor_mails.email FROM vendor_mails
                         LEFT JOIN
                         (SELECT DISTINCT vendor_code, vendor_name FROM material_controls) AS control
                         ON vendor_mails.vendor_code = control.vendor_code
                         WHERE remark = 'to') AS base_mail
                    LEFT JOIN
                    (SELECT vendor_mails.vendor_code, GROUP_CONCAT(vendor_mails.email) AS cc FROM vendor_mails
                         WHERE remark = 'cc'
                         GROUP BY vendor_mails.vendor_code) as cc
                    ON base_mail.vendor_code = cc.vendor_code
                    ORDER BY base_mail.vendor_code asc");

        return DataTables::of($vendor)
            ->addColumn('cc_mail', function ($vendor) {
                if ($vendor->cc != null) {
                    $cc = '';

                    if (str_contains($vendor->cc, ',')) {
                        $ccs = explode(',', $vendor->cc);

                        for ($i = 0; $i < count($ccs); $i++) {
                            $cc .= $ccs[$i];

                            if ($i != (count($ccs) - 1)) {
                                $cc .= '<br>';
                            }
                        }

                    } else {
                        $cc = $vendor->cc;
                    }

                } else {
                    return '';
                }

                return $cc;
            })
            ->rawColumns([
                'cc_mail' => 'cc_mail',
            ])
            ->make(true);

    }

    public function fetchControlDelivery(Request $request)
    {

        $data = db::table('material_plan_deliveries')
            ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            ->leftJoin('employee_syncs', 'material_controls.control', '=', 'employee_syncs.employee_id')
            ->where('material_plan_deliveries.po_number', '>=', '1000000000');

        if (strlen($request->get('issue_from')) > 0) {
            $issue_from = date('Y-m-d', strtotime($request->get('issue_from')));
            $data = $data->where('material_plan_deliveries.issue_date', '>=', $issue_from);
        }

        if (strlen($request->get('issue_to')) > 0) {
            $issue_to = date('Y-m-d', strtotime($request->get('issue_to')));
            $data = $data->where('material_plan_deliveries.issue_date', '<=', $issue_to);
        }

        if (strlen($request->get('due_from')) > 0) {
            $due_from = date('Y-m-d', strtotime($request->get('due_from')));
            $data = $data->where('material_plan_deliveries.due_date', '>=', $due_from);
        }

        if (strlen($request->get('due_to')) > 0) {
            $due_to = date('Y-m-d', strtotime($request->get('due_to')));
            $data = $data->where('material_plan_deliveries.due_date', '<=', $due_to);
        }

        if ($request->get('control') != null) {
            $data = $data->whereIn('material_controls.control', $request->get('control'));
        }

        if ($request->get('vendor') != null) {
            $data = $data->whereIn('material_controls.vendor_code', $request->get('vendor'));
        }

        $data = $data->select(
            'material_controls.control',
            'employee_syncs.name',
            'material_plan_deliveries.material_number',
            'material_controls.material_description',
            'material_controls.vendor_code',
            'material_controls.vendor_name',
            'material_plan_deliveries.po_number',
            'material_plan_deliveries.item_line',
            'material_plan_deliveries.quantity',
            'material_plan_deliveries.eta_date',
            'material_plan_deliveries.issue_date',
            'material_plan_deliveries.due_date',
            'material_plan_deliveries.po_send',
            'material_plan_deliveries.po_send_at',
            'material_plan_deliveries.po_confirm',
            'material_plan_deliveries.po_confirm_at',
            'material_plan_deliveries.status',
            'material_plan_deliveries.do_number',
            'material_plan_deliveries.bc_document',
            'material_plan_deliveries.sppb',
            'material_plan_deliveries.bc_send_at'
        )
            ->orderBy('material_plan_deliveries.po_number', 'ASC')
            ->orderBy('material_plan_deliveries.item_line', 'ASC')
            ->orderBy('material_plan_deliveries.due_date', 'ASC')
            ->get();

        $response = array(
            'status' => true,
            'data' => $data,
        );
        return Response::json($response);

    }

    public function fetchInOut(Request $request)
    {

        $data = db::table('material_in_outs')
            ->leftJoin('material_controls', 'material_controls.material_number', '=', 'material_in_outs.material_number')
            ->leftJoin('employee_syncs', 'material_controls.control', '=', 'employee_syncs.employee_id');

        if (strlen($request->get('entry_from')) > 0) {
            $entry_from = date('Y-m-d', strtotime($request->get('entry_from')));
            $data = $data->where('material_in_outs.entry_date', '>=', $entry_from);
        }

        if (strlen($request->get('entry_to')) > 0) {
            $entry_to = date('Y-m-d', strtotime($request->get('entry_to')));
            $data = $data->where('material_in_outs.entry_date', '<=', $entry_to);
        }

        if (strlen($request->get('posting_from')) > 0) {
            $posting_from = date('Y-m-d', strtotime($request->get('posting_from')));
            $data = $data->where('material_in_outs.posting_date', '>=', $posting_from);
        }

        if (strlen($request->get('posting_to')) > 0) {
            $posting_to = date('Y-m-d', strtotime($request->get('posting_to')));
            $data = $data->where('material_in_outs.posting_date', '<=', $posting_to);
        }

        if ($request->get('movement') != null) {
            if ($request->get('movement') == 'IN') {
                $data = $data->whereNull('material_in_outs.receive_location');
            } elseif ($request->get('movement') == 'OUT') {
                $data = $data->whereNotNull('material_in_outs.receive_location');
            }
        }

        if ($request->get('control') != null) {
            $data = $data->whereIn('material_controls.control', $request->get('control'));
        }

        if ($request->get('vendor') != null) {
            $data = $data->whereIn('material_controls.vendor_code', $request->get('vendor'));
        }

        $data = $data->select(
            'material_in_outs.entry_date',
            'material_in_outs.posting_date',
            'material_in_outs.material_number',
            'material_controls.material_description',
            'material_controls.vendor_code',
            'material_controls.vendor_name',
            'material_in_outs.po_number',
            'material_in_outs.item_line',
            'material_in_outs.quantity',
            'material_in_outs.issue_location',
            'material_in_outs.receive_location',
            'material_controls.control',
            'employee_syncs.name',
            'material_in_outs.bc_document'
        )
            ->orderBy('material_in_outs.entry_date', 'ASC')
            ->orderBy('material_in_outs.material_number', 'ASC')
            ->get();

        $response = array(
            'status' => true,
            'data' => $data,
        );
        return Response::json($response);

    }

    public function fetchProductionPlan()
    {

        $material = db::select("SELECT * FROM material_controls
                    WHERE purchasing_group = 'G15'");

        $plan = db::select("SELECT DATE_FORMAT(resume.forecast_month, '%Y-%m') as `month`, ratio.material_number, SUM((resume.quantity * ratio.ratio)) AS quantity FROM
                    (SELECT f.forecast_month, h.hpl, SUM(f.quantity) AS quantity FROM production_forecasts f
                         LEFT JOIN hpls h ON h.material_number =  f.material_number
                         WHERE f.forecast_month BETWEEN '2021-04-01' AND '2022-04-01'
                         GROUP BY f.forecast_month, h.hpl) AS resume
                    LEFT JOIN indirect_material_plan_ratios ratio
                    ON ratio.hpl = resume.hpl
                    GROUP BY resume.forecast_month, ratio.material_number");

        $start = new DateTime('2021-11-01');
        $start->modify('first day of this month');
        $end = new DateTime('2022-03-01');
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $interval_month = [];
        foreach ($period as $dt) {
            $row = array();
            $row['month'] = $dt->format("Y-m");
            $row['text_month'] = $dt->format("F");
            $row['text_year'] = $dt->format("Y");

            array_push($interval_month, $row);
        }

        $response = array(
            'status' => true,
            'material' => $material,
            'plan' => $plan,
            'interval' => $interval_month,
        );
        return Response::json($response);

    }

    public function fetchMaterialList(Request $request)
    {

        $material = db::select("SELECT material_controls.*, buyer.name AS buyer_name, control.name AS control_name FROM material_controls
                    LEFT JOIN employee_syncs AS buyer ON buyer.employee_id = material_controls.pic
                    LEFT JOIN employee_syncs AS control ON control.employee_id = material_controls.control
                    ORDER BY controlling_group, material_number ASC");

        $response = array(
            'status' => true,
            'material' => $material,
        );
        return Response::json($response);

    }

    public function fetchStockPolicy(Request $request)
    {

        $month = $request->get('month');
        if (strlen($month) <= 0) {
            $month = date('Y-m');
        }

        $material = db::select("SELECT * FROM material_controls
                    ORDER BY controlling_group, material_number ASC");

        $mpdl = db::select("SELECT * FROM material_plant_data_lists WHERE valcl IN ('9040', '9041')");

        $next_month = date('Y-m-01', strtotime('+1 month', strtotime($month . '-01')));
        $prev_month = date('Y-m-01', strtotime('-1 month', strtotime($month . '-01')));

        $policies = db::select("SELECT DATE_FORMAT(period, '%Y-%m') as `month`, material_number, policy, day FROM material_stock_policies
                    WHERE period BETWEEN '" . $prev_month . "' AND '" . $next_month . "'");

        $start = new DateTime($prev_month);
        $start->modify('first day of this month');
        $end = new DateTime($next_month);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $interval_month = [];
        foreach ($period as $dt) {
            $row = array();
            $row['month'] = $dt->format("Y-m");
            $row['text_month'] = $dt->format("F");
            $row['text_year'] = $dt->format("Y");

            array_push($interval_month, $row);
        }

        $response = array(
            'status' => true,
            'month' => date('F Y', strtotime($month . '-01')),
            'mpdl' => $mpdl,
            'material' => $material,
            'policies' => $policies,
            'interval' => $interval_month,
        );
        return Response::json($response);

    }

    public function fetchForecastUsage()
    {

        $material = db::select("SELECT * FROM material_controls
                    WHERE purchasing_group = 'G15'");

        $forecast_usage = db::select("SELECT DATE_FORMAT(`month`, '%Y-%m') as `month`, material_number, quantity FROM `production_plans`");

        $start = new DateTime('2021-11-01');
        $start->modify('first day of this month');
        $end = new DateTime('2022-03-01');
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $interval_month = [];
        foreach ($period as $dt) {
            $row = array();
            $row['month'] = $dt->format("Y-m");
            $row['text_month'] = $dt->format("F");
            $row['text_year'] = $dt->format("Y");

            array_push($interval_month, $row);
        }

        $response = array(
            'status' => true,
            'material' => $material,
            'forecast_usage' => $forecast_usage,
            'interval' => $interval_month,
        );
        return Response::json($response);

    }

    public function fetchMrp(Request $request)
    {

        $month = $request->get('month');
        if (strlen($month) <= 0) {
            $month = date('Y-m');
        }

        $month = $month . '-01';
        $until = date("Y-m-d", strtotime("+4 months", strtotime($month)));

        $material = db::select("SELECT mc.material_number, mc.material_description, CEIL(IF(mc.category = 'LOKAL', lokal.quantity, `import`.quantity)) AS safety, mc.lead_time, mc.minimum_order, mc.multiple_order, mc.dts, indm.expired FROM material_controls mc
                    LEFT JOIN
                    (SELECT material_number, AVG(quantity) AS quantity FROM production_plans
                         WHERE `month` BETWEEN '" . $month . "' AND '" . $until . "'
                         GROUP BY material_number) AS lokal
                         ON mc.material_number = lokal.material_number
                         LEFT JOIN
                         (SELECT material_number, AVG(quantity) AS quantity FROM production_plans
                         WHERE `month` BETWEEN '" . $month . "' AND '" . $until . "'
                         GROUP BY material_number) AS `import`
                         ON mc.material_number = `import`.material_number
                         LEFT JOIN indirect_materials indm ON indm.material_number = mc.material_number
                         WHERE mc.purchasing_group = 'G15'
                         ORDER BY mc.material_description ASC");

        $mrp = db::select("SELECT DATE_FORMAT(`month`, '%Y-%m') as `month`, material_number, quantity FROM `mrp`");

        $forecast_usage = db::select("SELECT DATE_FORMAT(`month`, '%Y-%m') as `month`, material_number, quantity FROM `production_plans`");

        $bo = db::select("SELECT DATE_FORMAT(`delivery_month`, '%Y-%m') as `month`, material_number, quantity FROM material_back_orders
                         WHERE mrp_month = '2021-11-01'");

        $grgi = db::select("SELECT * FROM grgi_logs
                         WHERE month = '" . $month . "'
                         AND location = 'MSTK' ");

        $start = new DateTime($month);
        $start->modify('first day of this month');
        $end = new DateTime($until);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $interval_month = [];
        foreach ($period as $dt) {
            $row = array();
            $row['month'] = $dt->format("Y-m");
            $row['text_month'] = $dt->format("F");
            $row['text_year'] = $dt->format("Y");

            $dates = db::select("SELECT week_date FROM weekly_calendars
                              WHERE remark <> 'H'
                              AND week_date LIKE '%" . $dt->format("Y-m") . "%'
                              ORDER BY week_date ASC");

            $txt = '';
            foreach ($dates as $date) {
                $txt = $txt . $date->week_date . ';';
            }

            $row['count'] = count($dates);
            $row['txt'] = $txt;

            array_push($interval_month, $row);
        }

        $response = array(
            'status' => true,
            'material' => $material,
            'bo' => $bo,
            'grgi' => $grgi,
            'mrp' => $mrp,
            'forecast_usage' => $forecast_usage,
            'interval' => $interval_month,
        );
        return Response::json($response);

    }

    public function fetchReportGrgi(Request $request)
    {
        $month = '';
        if (strlen($request->get('month')) > 0) {
            $month = $request->get('month');
        } else {
            $month = date('Y-m');
        }

        $valcl = '';
        if (strlen($request->get('valcl')) > 0) {
            $month = "AND grgi.valcl = '" . $request->get('valcl') . "'";
        }

        $location = db::select("SELECT category, storage_location FROM storage_locations
                         WHERE plnt = 8190
                         AND category IS NOT NULL
                         AND category <> 'REPAIR'
                         ORDER BY category, location");

        $grgi = GrgiLog::where('month', $month . "-01")->get();

        $resume = db::select("SELECT sloc.category, grgi.material_number, SUM(grgi.ending_quantity) AS ending_quantity, SUM(grgi.ending_amount) AS ending_amount FROM grgi_logs grgi
                         LEFT JOIN (SELECT category, storage_location FROM storage_locations
                              WHERE plnt = 8190
                              AND category IS NOT NULL
                              AND category <> 'REPAIR'
                              ORDER BY category, location) AS sloc
                         ON sloc.storage_location = grgi.location
                         WHERE grgi.`month` = '" . $month . "-01'
                         AND sloc.category IS NOT NULL
                         " . $valcl . "
                         GROUP BY sloc.category, grgi.material_number");

        $material = db::select("SELECT DISTINCT grgi.material_number, mpdl.material_description, grgi.valcl FROM grgi_logs grgi
                         LEFT JOIN material_plant_data_lists mpdl ON grgi.material_number = mpdl.material_number
                         WHERE grgi.`month` = '" . $month . "-01'
                         " . $valcl . "
                         ORDER BY grgi.material_number ASC");

        $response = array(
            'status' => true,
            'month_text' => date('F Y', strtotime($month . "-01")),
            'location' => $location,
            'grgi' => $grgi,
            'resume' => $resume,
            'material' => $material,
        );
        return Response::json($response);

    }

    public function fetchDailyPlanUsage(Request $request)
    {
        $month = '';
        if (strlen($request->get('month')) > 0) {
            $month = $request->get('month');
        } else {
            $month = date('Y-m');
        }

        $vendor_code = '';
        if ($request->get('vendor_code') != null) {
            $vendor_codes = $request->get('vendor_code');
            for ($i = 0; $i < count($vendor_codes); $i++) {
                $vendor_code = $vendor_code . "'" . $vendor_codes[$i] . "'";
                if ($i != (count($vendor_codes) - 1)) {
                    $vendor_code = $vendor_code . ',';
                }
            }
            $vendor_code = " WHERE mc.vendor_code IN (" . $vendor_code . ") ";
        }

        $buyer = '';
        if ($request->get('buyer') != null) {
            if (strlen($vendor_code) > 0) {
                $buyer = " AND mc.pic = '" . $request->get('buyer') . "' ";
            } else {
                $buyer = " WHERE mc.pic = '" . $request->get('buyer') . "' ";
            }
        }

        $calendar = db::select("SELECT week_date, DATE_FORMAT(week_date, '%d') AS date FROM weekly_calendars
                         WHERE DATE_FORMAT(week_date,'%Y-%m') = '" . $month . "'");

        $material = db::select("SELECT mc.material_number, mc.material_description, mc.vendor_code, mc.vendor_name, mpdl.bun, mc.category, emp.`name`, mc.remark FROM material_controls mc
                         LEFT JOIN material_plant_data_lists mpdl ON mpdl.material_number = mc.material_number
                         LEFT JOIN employee_syncs emp ON emp.employee_id = mc.pic"
            . $vendor_code
            . $buyer);

        $usage = db::select("SELECT ps.due_date, smbmr.raw_material, SUM((smbmr.`usage` * ps.quantity)) AS `usage` FROM
                         (SELECT ps.due_date, ps.material_number, ps.quantity FROM production_schedules ps
                              LEFT JOIN materials m ON m.material_number = ps.material_number
                              WHERE DATE_FORMAT(ps.due_date, '%Y-%m') = '" . $month . "'
                              AND m.category = 'FG'
                              UNION
                              SELECT ps.due_date, ps.material_number, ps.quantity FROM production_schedules_one_steps ps
                              LEFT JOIN materials m ON m.material_number = ps.material_number
                              WHERE DATE_FORMAT(ps.due_date, '%Y-%m') = '" . $month . "'
                              AND m.category = 'KD') AS ps
                              LEFT JOIN
                              (SELECT material_parent, raw_material, SUM(smbmrs.`usage`) AS `usage` FROM smbmrs
                              GROUP BY material_parent, raw_material) AS smbmr
                              ON smbmr.material_parent = ps.material_number
                              GROUP BY ps.due_date, smbmr.raw_material");

        $response = array(
            'status' => true,
            'month_text' => date('F Y', strtotime($month . "-01")),
            'calendar' => $calendar,
            'material' => $material,
            'usage' => $usage,
        );
        return Response::json($response);
    }

    public function fetchMonthlyPlanUsage(Request $request)
    {
        $month = '';
        if (strlen($request->get('month')) > 0) {
            $month = $request->get('month');
        } else {
            $month = date('Y-m');
        }

        $vendor_code = '';
        if ($request->get('vendor_code') != null) {
            $vendor_codes = $request->get('vendor_code');
            for ($i = 0; $i < count($vendor_codes); $i++) {
                $vendor_code = $vendor_code . "'" . $vendor_codes[$i] . "'";
                if ($i != (count($vendor_codes) - 1)) {
                    $vendor_code = $vendor_code . ',';
                }
            }
            $vendor_code = " WHERE mc.vendor_code IN (" . $vendor_code . ") ";
        }

        $buyer = '';
        if ($request->get('buyer') != null) {
            if (strlen($vendor_code) > 0) {
                $buyer = " AND mc.pic = '" . $request->get('buyer') . "' ";
            } else {
                $buyer = " WHERE mc.pic = '" . $request->get('buyer') . "' ";
            }
        }

        $calendar = db::select("SELECT DISTINCT DATE_FORMAT(forecast_month, '%Y-%m') AS `month`, MONTHNAME(forecast_month) AS text_month, YEAR(forecast_month) AS text_year FROM production_forecasts
                              WHERE DATE_FORMAT(forecast_month, '%Y-%m') >= '" . $month . "'
                              ORDER BY forecast_month ASC");

        $min_interval = date('Y-m-d', strtotime($calendar[count($calendar) - 1]->month . ' +1 month'));
        $fy_year = date('Y', strtotime($month . '-01 +2 year'));
        $fy_month = '03';
        $max_interval = $fy_year . '-' . $fy_month;

        $start = new DateTime($min_interval);
        $start->modify('first day of this month');
        $end = new DateTime($max_interval);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $interval_month = [];
        foreach ($period as $dt) {
            $row = array();
            $row['month'] = $dt->format("Y-m");
            $row['text_month'] = $dt->format("F");
            $row['text_year'] = $dt->format("Y");

            array_push($interval_month, $row);
        }

        $material = db::select("SELECT mc.material_number, mc.material_description, mc.vendor_code, mc.vendor_name, mpdl.bun, mc.category, emp.`name`, mc.remark FROM material_controls mc
                              LEFT JOIN material_plant_data_lists mpdl ON mpdl.material_number = mc.material_number
                              LEFT JOIN employee_syncs emp ON emp.employee_id = mc.pic"
            . $vendor_code
            . $buyer);

        $usage = db::select("SELECT DATE_FORMAT(f.forecast_month, '%Y-%m') AS `month`, smbmr.raw_material, SUM((smbmr.`usage` * f.quantity)) AS `usage` from production_forecasts f
                              LEFT JOIN (SELECT material_parent, raw_material, SUM(smbmrs.`usage`) AS `usage` FROM smbmrs
                                   GROUP BY material_parent, raw_material) AS smbmr
                              ON smbmr.material_parent = f.material_number
                              WHERE f.forecast_month >= '" . $month . "-01'
                              GROUP BY f.forecast_month, smbmr.raw_material");

        $avg_usage = db::select("SELECT smbmr.raw_material, AVG((smbmr.`usage` * f.quantity)) AS `usage` from production_forecasts f
                              LEFT JOIN (SELECT material_parent, raw_material, SUM(smbmrs.`usage`) AS `usage` FROM smbmrs
                                   GROUP BY material_parent, raw_material) AS smbmr
                              ON smbmr.material_parent = f.material_number
                              WHERE f.forecast_month >= '" . $month . "-01'
                              GROUP BY smbmr.raw_material");

        $response = array(
            'status' => true,
            'calendar' => $calendar,
            'interval' => $interval_month,
            'min_interval' => date('Y-m', strtotime($min_interval)),
            'material' => $material,
            'usage' => $usage,
            'avg_usage' => $avg_usage,
        );
        return Response::json($response);
    }

    public function fetchSmbmr()
    {

        $data = db::select("SELECT smbmrs.material_parent,
                              smbmrs.material_parent_description,
                              smbmrs.raw_material,
                              smbmrs.raw_material_description,
                              smbmrs.uom,
                              smbmrs.pgr,
                              SUM(smbmrs.`usage`) AS `usage`,
                              MAX(smbmrs.updated_at) AS updated_at,
                              concat(SPLIT_STRING(users.`name`, ' ', 1), ' ', SPLIT_STRING(users.`name`, ' ', 2)) as `name`
                              FROM smbmrs
                              LEFT JOIN users ON users.id = smbmrs.created_by
                              GROUP BY smbmrs.material_parent,
                              smbmrs.material_parent_description,
                              smbmrs.raw_material,
                              smbmrs.raw_material_description,
                              smbmrs.uom,
                              smbmrs.pgr,
                              users.`name`
                              ORDER BY smbmrs.material_parent ASC");

        return DataTables::of($data)->make(true);

    }

    public function fetchMaterialControl(Request $request)
    {
        $material_control = MaterialControl::leftJoin(db::raw('(SELECT * FROM employee_syncs) AS buyer'), 'buyer.employee_id', '=', 'material_controls.pic')
            ->leftJoin(db::raw('(SELECT * FROM employee_syncs) AS control'), 'control.employee_id', '=', 'material_controls.control')
            ->where('material_controls.purchasing_group', $request->get('purchasing_group'))
            ->select(
                'material_controls.remark',
                'material_controls.material_number',
                'material_controls.material_description',
                'material_controls.purchasing_group',
                'material_controls.vendor_code',
                'material_controls.vendor_name',
                'material_controls.category',
                db::raw('buyer.name AS buyer'),
                db::raw('control.name AS control'),
                'material_controls.remark'
            )
            ->orderBy('material_number', 'asc')
            ->get();

        $response = array(
            'status' => true,
            'material_control' => $material_control,
        );
        return Response::json($response);
    }

    public function fetchMaterialMonitoringSingle(Request $request)
    {
        $material_number = $request->get('material_number');
        $period = date('Y-m-d');
        $month = date('Y-m');

        $generates = self::generateMaterialMonitoringSingle($period, $material_number);

        $material_percentages = array();
        $results1 = array();
        $count_item = 0;

        foreach ($generates['policies'] as $policy) {
            if ($policy->material_number == $material_number) {
                $mpdl = MaterialPlantDataList::where('material_number', $policy->material_number)->first();

                $curr_policy = MaterialStockPolicy::where('material_number', $policy->material_number)
                    ->where(db::raw("date_format( period, '%Y-%m' )"), date('Y-m', strtotime($period)))
                    ->first();

                if ($curr_policy) {
                    $curr_policy = $curr_policy->policy;
                } else {
                    $curr_policy = 0;
                }

                $next_period = date('Y-m', strtotime('+1 month', strtotime($period)));
                $next_policy = MaterialStockPolicy::where('material_number', $policy->material_number)
                    ->where(db::raw("date_format( period, '%Y-%m' )"), date('Y-m', strtotime($next_period)))
                    ->first();

                if ($next_policy) {
                    $next_policy = $next_policy->policy;
                } else {
                    $next_policy = 0;
                }

                array_push($material_percentages, [
                    'material_number' => $policy->material_number,
                    'material_description' => $policy->material_description,
                    'bun' => $mpdl->bun,
                    'vendor_code' => $policy->vendor_code,
                    'vendor_name' => $policy->vendor_name,
                    'stock' => $policy->stock,
                    'day' => $policy->day,
                    'policy' => round($curr_policy),
                    'next_policy' => round($next_policy),
                    'ympipercentage' => round($policy->ympipercentage * 100, 2),
                    'percentage' => round($policy->percentage * 100, 2),
                ]);
                $count_item++;
            }
        }

        if (count($material_percentages) == 0) {
            $response = array(
                'status' => false,
                'message' => 'Stock Policy Not Found',
            );
            return Response::json($response);
        }

        $categories = array();
        $count_now = 0;
        $count_next = 0;

        foreach ($generates['materials'] as $material) {
            $stock_mstk = 0;
            $stock_wip = 0;
            $stock_total = 0;
            $usage = 0;
            $delivery_quantity = 0;
            $actual_usage = 0;
            $actual_delivery = 0;

            if ($material->material_number == $material_number) {
                if (!in_array(date('D, d M y', strtotime($material->due_date)), $categories)) {
                    array_push($categories, date('D, d M y', strtotime($material->due_date)));

                    if (date('D, d M y', strtotime($period)) == date('D, d M y', strtotime($material->due_date))) {
                        $count_now = count($categories);
                    }

                    if (intval(date('d', strtotime($material->due_date))) <= 23) {
                        $count_next = count($categories);
                    }
                }

                foreach ($generates['stocks'] as $stock) {

                    if ($material->material_number == $stock->material_number && $material->due_date == $stock->stock_date) {
                        $stock_mstk = $stock->stock_mstk;
                        $stock_wip = $stock->stock_wip;
                        $stock_total = $stock->stock_total;
                    }

                }

                foreach ($generates['mrps'] as $mrp) {
                    if ($material->material_number == $mrp->material_number && $material->due_date == $mrp->due_date) {
                        $usage = $mrp->usage;
                    }
                }

                foreach ($generates['deliveries'] as $delivery) {
                    if ($material->material_number == $delivery->material_number && $material->due_date == $delivery->due_date) {
                        $delivery_quantity = $delivery->quantity;
                    }
                }

                foreach ($generates['material_ins'] as $material_in) {
                    if ($material->material_number == $material_in->material_number && $material->due_date == $material_in->posting_date) {
                        $actual_delivery = $material_in->quantity;
                    }
                }

                foreach ($generates['material_outs'] as $material_out) {
                    if ($material->material_number == $material_out->material_number && $material->due_date == $material_out->posting_date) {
                        $actual_usage = $material_out->quantity;
                    }
                }

                array_push($results1, [
                    'material_number' => $material->material_number,
                    'material_description' => $material->material_description,
                    'vendor_code' => $material->vendor_code,
                    'vendor_name' => $material->vendor_name,
                    'category' => $material->category,
                    'pic' => $material->pic,
                    'remark' => $material->remark,
                    'due_date' => $material->due_date,
                    'stock_mstk' => $stock_mstk,
                    'stock_wip' => $stock_wip,
                    'stock_total' => $stock_total,
                    'plan_delivery' => $delivery_quantity,
                    'actual_delivery' => $actual_delivery,
                    'plan_usage' => $usage,
                    'actual_usage' => $actual_usage,
                ]);
            }
        }

        $results2 = array();

        for ($i = 0; $i < count($results1); $i++) {
            $plan_stock = 0;

            if ($results1[$i]['due_date'] > $period) {
                $del = 0;
                if ($results2[count($results2) - 1]['plan_delivery'] == 0) {
                    $del = $results2[count($results2) - 1]['actual_delivery'];
                } else {
                    $del = $results2[count($results2) - 1]['plan_delivery'];
                }
                $plan_stock = $results2[count($results2) - 1]['stock_total'] + $results2[count($results2) - 1]['plan_stock'] + $del - $results2[count($results2) - 1]['plan_usage'];
            }

            array_push($results2, [
                'material_number' => $results1[$i]['material_number'],
                'material_description' => $results1[$i]['material_description'],
                'vendor_code' => $results1[$i]['vendor_code'],
                'vendor_name' => $results1[$i]['vendor_name'],
                'category' => $results1[$i]['category'],
                'pic' => $results1[$i]['pic'],
                'remark' => $results1[$i]['remark'],
                'due_date' => $results1[$i]['due_date'],
                'stock_mstk' => $results1[$i]['stock_mstk'],
                'stock_wip' => $results1[$i]['stock_wip'],
                'stock_total' => $results1[$i]['stock_total'],
                'plan_delivery' => $results1[$i]['plan_delivery'],
                'actual_delivery' => $results1[$i]['actual_delivery'],
                'plan_usage' => $results1[$i]['plan_usage'],
                'actual_usage' => $results1[$i]['actual_usage'],
                'plan_stock' => $plan_stock,
            ]);
        }

        $response = array(
            'status' => true,
            'count_now' => $count_now,
            'count_next' => $count_next,
            'period' => date('d M y', strtotime($period)),
            'categories' => $categories,
            'material_percentages' => $material_percentages,
            'results' => $results2,
            'count_item' => $count_item,
        );
        return Response::json($response);
    }

    public function fetchMaterialMonitoring(Request $request)
    {
        $controlling_group = $request->get('controlling_group');
        $period = date('Y-m-d');

        if (strlen($request->get('period')) > 0) {
            $period = $request->get('period');
        }

        $generates = self::generateMaterialMonitoring($period, $controlling_group);

        $material_percentages = array();
        $results1 = array();
        $count_item = 0;

        foreach ($generates['policies'] as $policy) {
            if ($policy->percentage < 1) {
                $mpdl = MaterialPlantDataList::where('material_number', $policy->material_number)->first();

                $curr_policy = MaterialStockPolicy::where('material_number', $policy->material_number)
                    ->where(db::raw("date_format( period, '%Y-%m' )"), date('Y-m', strtotime($period)))
                    ->first();

                if ($curr_policy) {
                    $curr_policy = $curr_policy->policy;
                } else {
                    $curr_policy = 0;
                }

                $next_period = date('Y-m', strtotime('+1 month', strtotime($period)));
                $next_policy = MaterialStockPolicy::where('material_number', $policy->material_number)
                    ->where(db::raw("date_format( period, '%Y-%m' )"), date('Y-m', strtotime($next_period)))
                    ->first();

                if ($next_policy) {
                    $next_policy = $next_policy->policy;
                } else {
                    $next_policy = 0;
                }

                array_push($material_percentages, [
                    'material_number' => $policy->material_number,
                    'material_description' => $policy->material_description,
                    'bun' => $mpdl->bun,
                    'vendor_code' => $policy->vendor_code,
                    'vendor_name' => $policy->vendor_name,
                    'stock' => $policy->stock,
                    'day' => $policy->day,
                    'policy' => round($curr_policy),
                    'next_policy' => round($next_policy),
                    'ympipercentage' => round($policy->ympipercentage * 100, 2),
                    'percentage' => round($policy->percentage * 100, 2),
                ]);
                $count_item++;
            }
        }

        if (count($material_percentages) == 0) {
            $response = array(
                'status' => false,
                'message' => 'Stock Policy Not Found',
            );
            return Response::json($response);
        }

        $categories = array();
        $count_now = 0;
        $count_next = 0;

        foreach ($generates['materials'] as $material) {
            $stock_wh = 0;
            $stock_wip = 0;
            $stock_total = 0;
            $usage = 0;
            $delivery_quantity = 0;
            $actual_usage = 0;
            $actual_delivery = 0;

            if (!in_array(date('D, d M y', strtotime($material->due_date)), $categories)) {
                array_push($categories, date('D, d M y', strtotime($material->due_date)));

                if (date('D, d M y', strtotime($period)) == date('D, d M y', strtotime($material->due_date))) {
                    $count_now = count($categories);
                }

                if (intval(date('d', strtotime($material->due_date))) <= 23) {
                    $count_next = count($categories);
                }
            }

            foreach ($generates['stocks'] as $stock) {

                if ($material->material_number == $stock->material_number && $material->due_date == $stock->stock_date) {
                    $stock_wh = $stock->stock_wh;
                    $stock_wip = $stock->stock_wip;
                    $stock_total = $stock->stock_total;
                }

            }

            foreach ($generates['mrps'] as $mrp) {
                if ($material->material_number == $mrp->material_number && $material->due_date == $mrp->due_date) {
                    $usage = $mrp->usage;
                }
            }

            foreach ($generates['deliveries'] as $delivery) {
                if ($material->material_number == $delivery->material_number && $material->due_date == $delivery->due_date) {
                    $delivery_quantity = $delivery->quantity;
                }
            }

            foreach ($generates['material_ins'] as $material_in) {
                if ($material->material_number == $material_in->material_number && $material->due_date == $material_in->posting_date) {
                    $actual_delivery = $material_in->quantity;
                }
            }

            foreach ($generates['material_outs'] as $material_out) {
                if ($material->material_number == $material_out->material_number && $material->due_date == $material_out->posting_date) {
                    $actual_usage = $material_out->quantity;
                }
            }

            array_push($results1, [
                'material_number' => $material->material_number,
                'material_description' => $material->material_description,
                'vendor_code' => $material->vendor_code,
                'vendor_name' => $material->vendor_name,
                'controlling_group' => $material->controlling_group,
                'pic' => $material->pic,
                'remark' => $material->remark,
                'due_date' => $material->due_date,
                'stock_wh' => $stock_wh,
                'stock_wip' => $stock_wip,
                'stock_total' => $stock_total,
                'plan_delivery' => $delivery_quantity,
                'actual_delivery' => $actual_delivery,
                'plan_usage' => $usage,
                'actual_usage' => $actual_usage,
            ]);
        }

        $results2 = array();

        for ($i = 0; $i < count($results1); $i++) {
            $plan_stock = 0;

            if ($results1[$i]['due_date'] > $period) {
                $del = 0;
                if ($results2[count($results2) - 1]['plan_delivery'] == 0) {
                    $del = $results2[count($results2) - 1]['actual_delivery'];
                } else {
                    $del = $results2[count($results2) - 1]['plan_delivery'];
                }

                $plan_stock = $results2[count($results2) - 1]['stock_total'] + $results2[count($results2) - 1]['plan_stock'] + $del - $results2[count($results2) - 1]['plan_usage'];
            }

            array_push($results2, [
                'material_number' => $results1[$i]['material_number'],
                'material_description' => $results1[$i]['material_description'],
                'vendor_code' => $results1[$i]['vendor_code'],
                'vendor_name' => $results1[$i]['vendor_name'],
                'controlling_group' => $results1[$i]['controlling_group'],
                'pic' => $results1[$i]['pic'],
                'remark' => $results1[$i]['remark'],
                'due_date' => $results1[$i]['due_date'],
                'stock_wh' => $results1[$i]['stock_wh'],
                'stock_wip' => $results1[$i]['stock_wip'],
                'stock_total' => $results1[$i]['stock_total'],
                'plan_delivery' => $results1[$i]['plan_delivery'],
                'actual_delivery' => $results1[$i]['actual_delivery'],
                'plan_usage' => $results1[$i]['plan_usage'],
                'actual_usage' => $results1[$i]['actual_usage'],
                'plan_stock' => $plan_stock,
            ]);
        }

        $response = array(
            'status' => true,
            'date' => intval(date('d', strtotime($period))),
            'count_now' => $count_now,
            'count_next' => $count_next,
            'period' => date('d M y', strtotime($period)),
            'categories' => $categories,
            'material_percentages' => $material_percentages,
            'results' => $results2,
            'count_item' => $count_item,
        );
        return Response::json($response);
    }

    public function generateMaterialMonitoringSingle($due_date, $material_number)
    {

        $int_date = intval(date('d', strtotime($due_date)));

        $period = date('Y-m', strtotime($due_date));

        $policy_period = date('Y-m', strtotime($due_date));
        if ($int_date > 23) {
            $policy_period = date('Y-m', strtotime('+14 day', strtotime($due_date)));
        }

        $first = date('Y-m-01', strtotime($due_date));
        $last = date('Y-m-t', strtotime($due_date));

        $policies = db::select("SELECT msp.period, msp.material_number, mc.material_description, mc.vendor_code, mc.vendor_name,
                              '" . $due_date . "' AS stock_date,
                              COALESCE ( s.stock_total, 0 ) AS stock, msp.day, msp.policy,
                              COALESCE(s.stock_total, 0) / msp.policy AS ympipercentage,
                              COALESCE(s.stock_wh, 0) / msp.policy AS percentage
                              FROM material_stock_policies AS msp
                              LEFT JOIN
                              (SELECT sls.material_number, sls.stock_date,
                              sum(IF( sl.category = 'WAREHOUSE', (sls.unrestricted + sls.inspection), 0 )) AS stock_wh,
                              sum(IF( sl.category = 'WIP', (sls.unrestricted + sls.inspection), 0 )) AS stock_wip,
                              sum( (sls.unrestricted + sls.inspection) ) AS stock_total
                              FROM storage_location_stocks AS sls
                              LEFT JOIN storage_locations AS sl ON sls.storage_location = sl.storage_location
                              WHERE sls.stock_date = '" . $due_date . "'
                              AND sls.material_number = '" . $material_number . "'
                              GROUP BY sls.material_number, sls.stock_date
                              ORDER BY sls.material_number ASC, sls.stock_date ASC
                              ) AS s
                              ON s.material_number = msp.material_number
                              LEFT JOIN material_controls mc ON mc.material_number = msp.material_number
                              WHERE msp.material_number = '" . $material_number . "'
                              AND msp.policy > 0
                              AND msp.material_number IN ( SELECT material_number FROM material_controls )
                              AND date_format( msp.period, '%Y-%m' ) = '" . $policy_period . "'
                              ORDER BY percentage ASC");

        $materials = db::select("SELECT mc.material_number, wc.week_date AS due_date, mc.material_description, mc.vendor_code, mc.vendor_name, mc.category, mc.pic, mc.remark
                              FROM weekly_calendars AS wc
                              CROSS JOIN material_controls AS mc
                              WHERE mc.deleted_at IS NULL
                              AND date_format( wc.week_date, '%Y-%m' ) = '" . $period . "'
                              AND mc.material_number = '" . $material_number . "'
                              AND wc.remark <> 'H'
                              ORDER BY mc.material_number ASC, wc.week_date ASC");

        $stocks = db::select("SELECT sls.material_number, sls.stock_date,
                              sum( IF( sl.storage_location IN ('MSTK', 'WPCS', 'WPRC', 'WPPN'), (sls.unrestricted + sls.inspection), 0 )) AS stock_mstk,
                              sum( IF ( sl.category = 'WIP', (sls.unrestricted + sls.inspection), 0 )) AS stock_wip,
                              sum( (sls.unrestricted + sls.inspection) ) AS stock_total
                              FROM storage_location_stocks AS sls
                              LEFT JOIN storage_locations AS sl ON sls.storage_location = sl.storage_location
                              WHERE date( sls.stock_date ) >= '" . $first . "'
                              AND date( sls.stock_date ) <= '" . $due_date . "'
                              AND sls.material_number = '" . $material_number . "'
                              AND sl.category IN ('WAREHOUSE', 'WIP')
                              GROUP BY sls.material_number, sls.stock_date
                              ORDER BY sls.material_number ASC, sls.stock_date ASC");

        $mrps = db::select("SELECT mrp.material_number, mrp.due_date, mrp.usage
                              FROM material_requirement_plans AS mrp
                              WHERE date_format( mrp.due_date, '%Y-%m' ) = '" . $period . "'
                              AND mrp.material_number = '" . $material_number . "'");

        $deliveries = db::select("SELECT mpd.material_number, mpd.due_date, mpd.quantity
                              FROM material_plan_deliveries AS mpd
                              WHERE date_format( mpd.due_date, '%Y-%m' ) = '" . $period . "'
                              AND mpd.material_number = '" . $material_number . "'");

        $material_ins = db::select("SELECT mio.posting_date, mio.material_number, sum( mio.quantity ) AS quantity
                              FROM material_in_outs AS mio
                              WHERE mio.issue_location IN ('MSTK', 'WPCS', 'WPRC', 'WPPN', 'MS11')
                              AND mio.movement_type IN ( '101', '102', '9T3', '9T4' )
                              AND date( mio.posting_date) >= '" . $first . "'
                              AND date( mio.posting_date) < '" . $due_date . "'
                              AND material_number = '" . $material_number . "'
                              GROUP BY mio.posting_date, mio.material_number");

        $material_outs = db::select("SELECT mio.entry_date AS posting_date, mio.material_number, sum( mio.quantity ) AS quantity
                              FROM material_in_outs AS mio
                              WHERE mio.issue_location = 'MSTK'
                              AND mio.movement_type IN ( 'SD01', 'SD02', 'SI91', 'SI92', '9I3', '9I4', '9OE' , '9OF' )
                              AND date( mio.entry_date) >= '" . $first . "'
                              AND date( mio.entry_date) < '" . $due_date . "'
                              AND material_number = '" . $material_number . "'
                              GROUP BY mio.entry_date, mio.material_number");

        return array(
            'policies' => $policies,
            'material_numbers' => [$material_number],
            'materials' => $materials,
            'stocks' => $stocks,
            'mrps' => $mrps,
            'deliveries' => $deliveries,
            'material_ins' => $material_ins,
            'material_outs' => $material_outs,
        );

    }

    public function generateMaterialMonitoring($due_date, $controlling_group)
    {

        $int_date = intval(date('d', strtotime($due_date)));

        $period = date('Y-m', strtotime($due_date));

        $policy_period = date('Y-m', strtotime($due_date));
        if ($int_date > 23) {
            $policy_period = date('Y-m', strtotime('+14 day', strtotime($due_date)));
        }

        $first = date('Y-m-01', strtotime($due_date));
        $last = date('Y-m-t', strtotime($due_date));

        $policies = db::select("SELECT msp.period, msp.material_number, mc.material_description, mc.controlling_group, mc.vendor_code, mc.vendor_name,
                              '" . $due_date . "' AS stock_date,
                              COALESCE(s.stock_total, 0) AS stock, msp.day, msp.policy,
                              COALESCE(s.stock_total, 0) / msp.policy AS ympipercentage,
                              COALESCE(s.stock_wh, 0) / msp.policy AS percentage
                              FROM material_stock_policies AS msp
                              LEFT JOIN
                              (SELECT sls.material_number, sls.stock_date,
                              sum(IF(sl.category = 'WAREHOUSE', (sls.unrestricted + sls.inspection), 0)) AS stock_wh,
                              sum(IF(sl.category = 'WIP', (sls.unrestricted + sls.inspection), 0)) AS stock_wip,
                              sum((sls.unrestricted + sls.inspection)) AS stock_total
                              FROM storage_location_stocks AS sls
                              LEFT JOIN storage_locations AS sl ON sls.storage_location = sl.storage_location
                              WHERE sls.stock_date = '" . $due_date . "'
                              AND sls.material_number IN ( SELECT material_number FROM material_controls WHERE deleted_at IS NULL )
                              GROUP BY sls.material_number, sls.stock_date
                              ORDER BY sls.material_number ASC, sls.stock_date ASC
                              ) AS s
                              ON s.material_number = msp.material_number
                              LEFT JOIN material_controls mc ON mc.material_number = msp.material_number
                              WHERE msp.policy > 0
                              AND msp.material_number in (SELECT material_number FROM material_controls)
                              AND mc.controlling_group = '" . $controlling_group . "'
                              AND date_format( msp.period, '%Y-%m' ) = '" . $policy_period . "'
                              ORDER BY percentage ASC");

        $material_numbers = array();

        foreach ($policies as $policy) {
            if ($policy->percentage < 1) {
                if (!in_array($policy->material_number, $material_numbers)) {
                    array_push($material_numbers, $policy->material_number);
                }
            }
        }

        $where_materials = "";

        $material_number = "";

        for ($x = 0; $x < count($material_numbers); $x++) {
            $material_number = $material_number . "'" . $material_numbers[$x] . "'";
            if ($x != count($material_numbers) - 1) {
                $material_number = $material_number . ",";
            }
        }
        $where_materials = $material_number;

        if ($where_materials == "") {
            $where_materials = "''";
        }

        $materials = db::select("SELECT mc.material_number, wc.week_date AS due_date, mc.material_description, mc.controlling_group, mc.vendor_code,
                              mc.vendor_name, mc.controlling_group, mc.pic, mc.remark
                              FROM weekly_calendars AS wc
                              CROSS JOIN material_controls AS mc
                              WHERE mc.deleted_at IS NULL
                              AND date_format( wc.week_date, '%Y-%m' ) = '" . $period . "'
                              AND mc.material_number in (" . $where_materials . ")
                              AND mc.controlling_group = '" . $controlling_group . "'
                              AND wc.remark <> 'H'
                              ORDER BY mc.material_number ASC, wc.week_date ASC");

        $stocks = db::select("SELECT sls.material_number, sls.stock_date,
                              sum(IF(sl.storage_location IN ('MSTK', 'WPCS', 'WPRC', 'WPPN'), (sls.unrestricted + sls.inspection), 0)) AS stock_wh,
                              sum(IF(sl.category = 'WIP', (sls.unrestricted + sls.inspection), 0)) AS stock_wip,
                              sum((sls.unrestricted + sls.inspection)) AS stock_total
                              FROM storage_location_stocks AS sls
                              LEFT JOIN storage_locations AS sl ON sls.storage_location = sl.storage_location
                              WHERE date( sls.stock_date ) >= '" . $first . "'
                              AND date( sls.stock_date ) <= '" . $due_date . "'
                              AND sls.material_number IN (" . $where_materials . ")
                              AND sl.category IN ('WAREHOUSE', 'WIP')
                              GROUP BY sls.material_number, sls.stock_date
                              ORDER BY sls.material_number ASC, sls.stock_date ASC");

        $mrps = db::select("SELECT mrp.material_number, mrp.due_date, mrp.usage
                              FROM material_requirement_plans AS mrp
                              WHERE date_format( mrp.due_date, '%Y-%m' ) = '" . $period . "'
                              AND mrp.material_number IN (" . $where_materials . ")");

        $deliveries = db::select("SELECT mpd.material_number,
                              mpd.due_date, mpd.quantity
                              FROM material_plan_deliveries AS mpd
                              WHERE date_format( mpd.due_date, '%Y-%m' ) = '" . $period . "'
                              AND mpd.material_number IN (" . $where_materials . ")");

        $material_ins = db::select("SELECT mio.posting_date, mio.material_number, sum(mio.quantity) AS quantity FROM material_in_outs AS mio
                              WHERE mio.issue_location IN ('MSTK', 'WPCS', 'WPRC', 'WPPN', 'MS11')
                              AND mio.movement_type IN ( '101', '102', '9T3', '9T4' )
                              AND date( mio.posting_date) >= '" . $first . "'
                              AND date( mio.posting_date) < '" . $due_date . "'
                              AND material_number IN (" . $where_materials . ")
                              GROUP BY mio.posting_date, mio.material_number");

        $material_outs = db::select("SELECT mio.posting_date, mio.material_number, sum(mio.quantity) AS quantity FROM material_in_outs AS mio
                              WHERE mio.issue_location IN ('MSTK', 'WPCS', 'WPRC', 'WPPN', 'MS11')
                              AND mio.movement_type IN ( 'SD01', 'SD02', 'SI91', 'SI92', '9I3', '9I4', '9OE', '9OF' )
                              AND date( mio.posting_date) >= '" . $first . "'
                              AND date( mio.posting_date) < '" . $due_date . "'
                              AND material_number IN (" . $where_materials . ")
                              GROUP BY mio.posting_date, mio.material_number");

        return array(
            'policies' => $policies,
            'material_numbers' => $material_numbers,
            'materials' => $materials,
            'stocks' => $stocks,
            'mrps' => $mrps,
            'deliveries' => $deliveries,
            'material_ins' => $material_ins,
            'material_outs' => $material_outs,
        );

    }

    public function indexMaterialRequest()
    {
        $title = 'Material Request';
        $title_jp = '??';
        $storage_locations = StorageLocation::select('location', 'storage_location')->distinct()
            ->orderBy('location', 'asc')
            ->get();

        return view('materials.request.request', array(
            'title' => $title,
            'title_jp' => $title_jp,
            'storage_locations' => $storage_locations,
        ))->with('page', 'Material Request')->with('Head', 'Material Delivery');
    }

    public function indexMaterialReceive()
    {

    }

    public function indexMaterialDelivery()
    {

    }

    public function fetchMaterialRequestList(Request $request)
    {
        $lists = ReturnAdditional::select('material_number', 'description', 'issue_location', 'receive_location', 'lot')
            ->where('receive_location', '=', $request->get('location'))
            ->orderBy('issue_location', 'asc')
            ->orderBy('material_number', 'asc')
            ->distinct()
            ->get();

        if (count($lists) == 0) {
            $response = array(
                'status' => false,
                'materials' => "Tidak ada material untuk lokasi tersebut.",
            );
            return Response::json($response);
        }

        $response = array(
            'status' => true,
            'materials' => "Lokasi berhasil dipilih.",
            'lists' => $lists,
        );
        return Response::json($response);
    }

    public function fetchMaterial()
    {
        $materials = Material::leftJoin("origin_groups", "origin_groups.origin_group_code", "=", "materials.origin_group_code")
            ->orderBy('material_number', 'ASC')
            ->select("materials.id", "materials.material_number", "materials.material_description", "materials.base_unit", "materials.issue_storage_location", "materials.mrpc", "materials.valcl", "origin_groups.origin_group_name", "materials.hpl", "materials.category", "materials.model")
            ->get();

        return DataTables::of($materials)
            ->addColumn('action', function ($materials) {
                return '
                              <button class="btn btn-xs btn-info" data-toggle="tooltip" title="Details" onclick="modalView(' . $materials->id . ')">View</button>
                              <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" onclick="modalEdit(' . $materials->id . ')">Edit</button>
                              <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" onclick="modalDelete(' . $materials->id . ',\'' . $materials->material_number . '\')">Delete</button>';
            })

            ->rawColumns(['action' => 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {

        try {
            $id = Auth::id();
            $material = new Material([
                'material_number' => $request->get('material_number'),
                'material_description' => $request->get('material_description'),
                'base_unit' => $request->get('base_unit'),
                'issue_storage_location' => $request->get('issue_storage_location'),
                'mrpc' => $request->get('mrpc'),
                'valcl' => $request->get('valcl'),
                'origin_group_code' => $request->get('origin_group_code'),
                'hpl' => $request->get('hpl'),
                'category' => $request->get('category'),
                'model' => $request->get('model'),
                'created_by' => $id,
            ]);

            $material->save();

            $response = array(
                'status' => true,
                'materials' => "New Material has been created.",
            );
            return Response::json($response);

        } catch (QueryException $e) {
            $error_code = $e->errorInfo[1];
            if ($error_code == 1062) {
                $response = array(
                    'status' => true,
                    'materials' => "Material already exist",
                );
                return Response::json($response);
            } else {
                $response = array(
                    'status' => true,
                    'materials' => "Material not created.",
                );
                return Response::json($response);
            }
        }

    }

    public function view(Request $request)
    {
        $query = "select mat.material_number, mat.base_unit, mat.issue_storage_location, users.`name`, material_description, origin_group_name, mat.created_at, mat.updated_at, mat.hpl, mat.category, mat.mrpc, mat.valcl from
                         (select material_number, material_description, base_unit, issue_storage_location, mrpc, valcl, origin_group_code, hpl, category, created_by, created_at, updated_at from materials where id = "
        . $request->get('id') . ") as mat
                         left join origin_groups on origin_groups.origin_group_code = mat.origin_group_code
                         left join users on mat.created_by = users.id";

        $material = DB::select($query);

        $response = array(
            'status' => true,
            'datas' => $material,
        );
        return Response::json($response);

    }

    public function fetchEdit(Request $request)
    {
        $hpls = $this->hpl;
        $categories = $this->category;
        $valcls = $this->valcl;
        $origin_groups = OriginGroup::orderBy('origin_group_code', 'ASC')->get();
        $material = Material::find($request->get("id"));

        $response = array(
            'status' => true,
            'datas' => $material,
        );
        return Response::json($response);
    }

    public function edit(Request $request)
    {
        try {
            $material = Material::find($request->get("id"));
            $material->material_description = $request->get('material_description');
            $material->base_unit = $request->get('base_unit');
            $material->issue_storage_location = $request->get('issue_storage_location');
            $material->mrpc = $request->get('mrpc');
            $material->valcl = $request->get('valcl');
            $material->origin_group_code = $request->get('origin_group_code');
            $material->hpl = $request->get('hpl');
            $material->category = $request->get('category');
            $material->model = $request->get('model');
            $material->save();

            $response = array(
                'status' => true,
            );
            return Response::json($response);

        } catch (QueryException $e) {
            $error_code = $e->errorInfo[1];
            if ($error_code == 1062) {
                $response = array(
                    'status' => true,
                    'datas' => "Material already exist",
                );
                return Response::json($response);
            } else {
                $response = array(
                    'status' => true,
                    'datas' => "Update Material Error.",
                );
                return Response::json($response);
            }
        }
    }

    public function delete(Request $request)
    {
        $material = Material::find($request->get("id"));
        $material->forceDelete();

        $response = array(
            'status' => true,
        );
        return Response::json($response);
    }

    public function import(Request $request)
    {
        if ($request->hasFile('material')) {
            Material::truncate();

            $id = Auth::id();

            $file = $request->file('material');
            $data = file_get_contents($file);

            $rows = explode("\r\n", $data);
            foreach ($rows as $row) {
                if (strlen($row) > 0) {
                    $row = explode("\t", $row);
                    $material_number = '';
                    if (strlen($row[0]) == 6) {
                        $material_number = "0" . $row[0];
                    } elseif (strlen($row[0]) == 5) {
                        $material_number = "00" . $row[0];
                    } else {
                        $material_number = $row[0];
                    }
                    $origin_group_code = '';
                    if (strlen($row[6]) == 2) {
                        $origin_group_code = "0" . $row[6];
                    } else {
                        $origin_group_code = $row[6];
                    }
                    $material = new Material([
                        'material_number' => $material_number,
                        'material_description' => $row[1],
                        'base_unit' => $row[2],
                        'issue_storage_location' => $row[3],
                        'mrpc' => $row[4],
                        'valcl' => $row[5],
                        'origin_group_code' => $origin_group_code,
                        'hpl' => $row[7],
                        'category' => $row[8],
                        'model' => $row[9],
                        'created_by' => $id,
                    ]);

                    $material->save();
                }
            }
            return redirect('/index/material')->with('status', 'New materials has been imported.')->with('page', 'Material');

        } else {
            return redirect('/index/material')->with('error', 'Please select a file.')->with('page', 'Material');
        }
    }

    public function breakdown()
    {

        Smbmr::truncate();

        $materials = db::select("SELECT DISTINCT f.material_number, mpdl.material_description FROM production_forecasts f
                              LEFT JOIN material_plant_data_lists mpdl ON mpdl.material_number = f.material_number");

        for ($i = 0; $i < count($materials); $i++) {
            $breakdown = db::select("SELECT b.material_parent, b.material_child, b.`usage`, b.divider, b.spt, b.valcl FROM bom_outputs b
                                   WHERE b.material_parent = '" . $materials[$i]->material_number . "'");

            for ($j = 0; $j < count($breakdown); $j++) {
                if ($breakdown[$j]->valcl != '9040') {
                    $row = array();
                    $row['material_parent'] = $breakdown[$j]->material_parent;
                    $row['material_child'] = $breakdown[$j]->material_child;
                    $row['quantity'] = $breakdown[$j]->usage / $breakdown[$j]->divider;
                    $row['created_at'] = Carbon::now();
                    $row['updated_at'] = Carbon::now();

                    $this->check[] = $row;
                } else {
                    $mpdl = MaterialPlantDataList::where('material_number', $breakdown[$j]->material_child)->first();

                    $pgr = array("G08", "G15", "999");

                    if (in_array($mpdl->pgr, $pgr)) {
                        $row = array();
                        $row['material_parent'] = $materials[$i]->material_number;
                        $row['material_parent_description'] = $materials[$i]->material_description;
                        $row['raw_material'] = $breakdown[$j]->material_child;
                        $row['raw_material_description'] = $mpdl->material_description;
                        $row['uom'] = $mpdl->bun;
                        $row['pgr'] = $mpdl->pgr;
                        $row['usage'] = $breakdown[$j]->usage / $breakdown[$j]->divider;
                        $row['created_by'] = Auth::id();
                        $row['created_at'] = Carbon::now();
                        $row['updated_at'] = Carbon::now();

                        $this->output[] = $row;
                    }
                }
            }
        }

        while (count($this->check) > 0) {
            $this->breakdownLoop();
        }

        foreach (array_chunk($this->output, 1000) as $t) {
            $output = Smbmr::insert($t);
        }

        $response = array(
            'status' => true,
        );
        return Response::json($response);
    }

    public function breakdownLoop()
    {

        $this->temp = array();

        for ($i = 0; $i < count($this->check); $i++) {
            $breakdown = db::select("SELECT b.material_parent, b.material_child, b.`usage`, b.divider, b.spt, b.valcl FROM bom_outputs b
                                   WHERE b.material_parent = '" . $this->check[$i]['material_child'] . "'");

            for ($j = 0; $j < count($breakdown); $j++) {
                if ($breakdown[$j]->valcl != '9040') {
                    $row = array();
                    $row['material_parent'] = $this->check[$i]['material_parent'];
                    $row['material_child'] = $breakdown[$j]->material_child;
                    $row['quantity'] = round($this->check[$i]['quantity'] * ($breakdown[$j]->usage / $breakdown[$j]->divider), 6);
                    $row['created_at'] = Carbon::now();
                    $row['updated_at'] = Carbon::now();

                    $this->temp[] = $row;
                } else {
                    $mpdl = MaterialPlantDataList::where('material_number', $breakdown[$j]->material_child)->first();
                    $parent = MaterialPlantDataList::where('material_number', $this->check[$i]['material_parent'])->first();

                    $pgr = array("G08", "G15", "999");

                    if (in_array($mpdl->pgr, $pgr)) {
                        $row = array();
                        $row['material_parent'] = $this->check[$i]['material_parent'];
                        $row['material_parent_description'] = $parent->material_description;
                        $row['raw_material'] = $breakdown[$j]->material_child;
                        $row['raw_material_description'] = $mpdl->material_description;
                        $row['uom'] = $mpdl->bun;
                        $row['pgr'] = $mpdl->pgr;
                        $row['usage'] = round($this->check[$i]['quantity'] * ($breakdown[$j]->usage / $breakdown[$j]->divider), 6);
                        $row['created_by'] = Auth::id();
                        $row['created_at'] = Carbon::now();
                        $row['updated_at'] = Carbon::now();

                        $this->output[] = $row;
                    }
                }
            }
        }

        $this->check = array();
        $this->check = $this->temp;
    }

    public function sendPoNotification(Request $request)
    {

        $exclude = [
            'Y31504',
            'Y10022',
            'Y81801',
        ];

        $bcc = [
            'aditya.agassi@music.yamaha.com',
            'muhammad.ikhlas@music.yamaha.com',
        ];

        $po_number = $request->get('po_number');

        $delivery = MaterialPlanDelivery::where('po_number', $po_number)->first();

        $notes = MaterialPlanDelivery::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            ->where('po_number', $po_number)
            ->whereNotNull('note')
            ->select(
                'material_plan_deliveries.*',
                'material_controls.material_description'
            )
            ->get();

        $material = MaterialPlanDelivery::leftJoin('material_controls', 'material_controls.material_number', '=', 'material_plan_deliveries.material_number')
            ->leftJoin('users AS buyer_proc', 'buyer_proc.username', '=', 'material_controls.pic')
            ->leftJoin('users AS control_proc', 'control_proc.username', '=', 'material_controls.control')
            ->where('po_number', $po_number)
            ->select(
                'material_controls.vendor_code',
                'material_controls.vendor_name',
                db::raw('buyer_proc.email AS buyer_email'),
                db::raw('control_proc.email AS control_email')
            )
            ->first();

        $attention = '';
        $to = [];
        $cc = [
            $material->buyer_email,
            $material->control_email,
        ];
        $vendor_mails = VendorMail::where('vendor_code', $material->vendor_code)->get();
        for ($j = 0; $j < count($vendor_mails); $j++) {
            if ($vendor_mails[$j]->remark == 'to') {
                $attention = $vendor_mails[$j]->name;
                $to[] = $vendor_mails[$j]->email;
            } else {
                $cc[] = $vendor_mails[$j]->email;
            }
        }

// If Yamaha Group
        if (in_array($material->vendor_code, $exclude)) {
            $to = [
                $material->buyer_email,
                $material->control_email,
            ];
            $cc = [];
        }

        $data = [
            'buyer' => $material->buyer_email,
            'control' => $material->control_email,
            'po_number' => $po_number,
            'vendor_code' => $material->vendor_code,
            'vendor_name' => $material->vendor_name,
            'subject' => 'PO CONFIRMATION REPORT : ' . $po_number . '_' . $material->vendor_name,
            'delivery' => $delivery,
            'notes' => $notes,
        ];

        try {
            Mail::to($to)
                ->cc($cc)
                ->bcc($bcc)
                ->send(new SendEmail($data, 'raw_material_send_po_notification'));

            $response = array(
                'status' => true,
            );
            return Response::json($response);
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'message' => $e->getMessage(),
            );
            return Response::json($response);

        }
    }
}
