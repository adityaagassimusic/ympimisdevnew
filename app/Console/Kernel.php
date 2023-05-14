<?php

namespace App\Console;

use App\BatchSetting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\GenerateEfficiency::class,
        Commands\AttendanceViolation::class,
        Commands\UploadCompletions::class,
        Commands\UploadTransfers::class,
        Commands\PlanStamps::class,
        Commands\Leaves::class,
        Commands\SendEmailShipments::class,
        Commands\SendEmailOvertimes::class,
        Commands\EmailMiddleKanban::class,
        Commands\EmailConfirmationOvertimes::class,
        Commands\EmailUserDocument::class,
        Commands\EmailClinicVisit::class,
        Commands\InjectionScheduleTrial::class,
        Commands\InjectionVisualCheckCommand::class,
        Commands\CallRoute::class,
        Commands\LeaveRequestSunfishCommand::class,
        Commands\PaySlipCommand::class,
        Commands\UploadCompletionKD::class,
        Commands\UploadTransferKD::class,
        Commands\SyncSunfish::class,
        Commands\SyncEmpYmpicoid::class,
        Commands\SendEmailKaizen::class,
        Commands\EmployeeHistory::class,
        Commands\EmailVisitorConfirmation::class,
        // Commands\SendMachineNotification::class,
        Commands\EmailHrq::class,
        Commands\PowerMonitor::class,
        Commands\RoomTemperatureLog::class,
        Commands\RoomTemperatureEmail::class,
        // Commands\SurveyCovid::class,
        Commands\UpdateAddress::class,
        // Commands\HighestCovidCommand::class,
        Commands\MCUCommand::class,
        Commands\MCUReminder::class,
        Commands\PianicaCommand::class,
        Commands\DailyMaintenanceCommand::class,
        // Commands\APARAutoPR::class,
        // Commands\SchedulingChemical::class,
        // Commands\SendEmailChemicalNotInput::class,
        // Commands\SendEmailChemicalUnpicked::class,
        Commands\InterviewPointingCallCommand::class,
        Commands\UpdatePointingCall::class,
        Commands\SkillUnfulfilledLogCommand::class,
        Commands\CostCenterHistoryCommand::class,
        Commands\InjectionScheduleCommand::class,
        Commands\KDShipment::class,
        Commands\SendEmailSPKNotification::class,
        Commands\SendEmailSPK::class,
        Commands\EmailAgreement::class,
        Commands\LiveCookingCommand::class,
        Commands\GeneralAffairsCommand::class,
        Commands\GreatdayAttendanceCommand::class,
        Commands\GeocodeUpdate::class,
        Commands\SyncShiftSunfish::class,

        Commands\SalesReport::class,
        Commands\ResumeNgBuffing::class,
        Commands\ResumeNgLacquering::class,
        Commands\ResumeNgPlating::class,
        // Commands\ResumeNgBody::class,
        Commands\EmailBento::class,
        Commands\DoubleTransactionNotification::class,
        Commands\ResetOperatorLocation::class,

        Commands\GenerateInitialSafetyStock::class,
        Commands\CustomBreakdown::class,
        Commands\GenerateBomScrap::class,

        Commands\SyncVendor::class,

        //RAW MATERIAL
        Commands\GenerateSmbmr::class,
        Commands\SyncPlanDelivery::class,
        Commands\RawMaterialReminder::class,
        Commands\RawMaterialOverUsage::class,
        Commands\RawMaterialSendPo::class,
        Commands\RawMaterialReminderPo::class,
        Commands\RawMaterialReminderDelivery::class,
        // Commands\RawMaterialSendBc::class,

        //Extra Order
        Commands\ReminderExtraOrder::class,

        //Tools
        // Commands\ToolsOrderReminder::class,

        // Patrol
        Commands\PatrolFinding::class,
        Commands\PatrolReminder::class,
        Commands\ReminderDeliveryEquipment::class,
        Commands\ReminderPenerimaanBarang::class,
        Commands\ReminderDeliveryCanteen::class,

        Commands\SkillMapCommand::class,
        Commands\AuditExternalClaimCommand::class,
        Commands\CarDocumentCommand::class,

        //KITTO
        Commands\UploadTransferKitto::class,
        Commands\UploadCompletionKitto::class,
        Commands\RecordDailyStocks::class,
        Commands\RecordStockMiddle::class,

        //WH
        // Commands\UpdateStatusOperator::class,

        Commands\OperatorWhInternal::class,
        Commands\GeneralDaily::class,
        Commands\GeneralMonthly::class,
        // Commands\GeneralHourly::class,
        Commands\GeneralMinute::class,
        Commands\EmailSafetyRiding::class,
        Commands\SendMailAttendanceRate::class,
        Commands\LicenseReminder::class,
        Commands\MAppCommand::class,
        Commands\ReminderCMS::class,
        Commands\ReminderLocker::class,
        Commands\ReminderThreeMDocument::class,
        Commands\ReminderThreeM::class,

        //QA Certificate
        Commands\QaCertificateCommand::class,
        // Commands\QualityAssuranceCommand::class,

        //SDS
        Commands\SDSRimender::class,

        //GS
        Commands\ReminderGS::class,

        //Smart Recruitment
        // Commands\RequestMp::class,
        // Commands\EndContractEmployee::class,

        Commands\GenerateData::class,

        //YMES
        Commands\YMESInterface::class,
        Commands\YMESError::class,
        Commands\YMESErrorInterface::class,
        Commands\YMESSync::class,
        Commands\YMESUnmatchTransaction::class,
        Commands\MaterialCheck::class,
        Commands\MaterialCheckEmail::class,

        // Commands\Trial::class,

        // Commands\ReminderCIPFixedAsset::class,
        Commands\NotificationKy::class,
        Commands\BirthdayEmployee::class,
        Commands\OvertimeHoliday::class,

        // Commands\ScheduleYMESDay::class,

        //GS
        Commands\ScheduleGS::class,
        //limbah b3
        Commands\ReminderLoadingLimbah::class,


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // BUTUH PUBLIC
        // $schedule->command('email:patrol')->dailyAt('12:30');
        // $schedule->command('email:patrol')->dailyAt('16:00');
        // $schedule->command('email:patrol')->dailyAt('03:00');
        // $schedule->command('reminder:cms')->quarterly('09:00');
        // $schedule->command('sync:leave_request')->everyThirtyMinutes();


        // TIDAK BUTUH PUBLIC
        $schedule->command('general:daily')->dailyAt('02:20');
        $schedule->command('update:pointing_calls')->dailyAt('02:25');
        $schedule->command('raw_material:send_po')->hourlyAt(15);
        $schedule->command('raw_material:send_po')->hourlyAt(45);
        $schedule->command('sync:bfv_vendor')->hourly();
        $schedule->command('raw_material:reminder_po')->dailyAt('07:20');
        $schedule->command('email:bento')->weeklyOn(5, '08:10');
        $schedule->command('email:overtime')->weekdays()->dailyAt('08:50');
        $schedule->command('email:attendance')->weekdays()->dailyAt('09:00');
        $schedule->command('generate:efficiency')->dailyAt('02:00');
        $schedule->command('sync:sunfish')->dailyAt('04:30');
        $schedule->command('material:check')->everyThirtyMinutes();
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('06:00');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('11:00');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('13:05');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('17:30');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('19:30');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('01:15');
        $schedule->command('sync:shift_sunfish')->weekdays()->dailyAt('01:45');
        $schedule->command('employee:history')->monthlyOn(date('t'), '18:40');
        $schedule->command('email:kaizen')->weekdays()->dailyAt('09:45');
        $schedule->command('email:hrq')->weekdays()->dailyAt('05:30');
        // $schedule->command('reminder:ky')->weekly()->mondays()->at('08:00');
        $schedule->command('mtc:op_reset 1')->weekdays()->dailyAt('07:00');
        $schedule->command('mtc:op_reset 2')->weekdays()->dailyAt('16:30');
        $schedule->command('mtc:op_reset 3')->weekdays()->dailyAt('00:30');
        $schedule->command('daily:maintenance')->dailyAt('01:10');
        $schedule->command('costcenter:history')->dailyAt('01:00');
        $schedule->command('spk:notify')->everyThirtyMinutes();
        $schedule->command('reminder:threeM')->weekdays()->dailyAt('12:00');
        $schedule->command('email:agreement')->dailyAt('10:00');

        // $schedule->command('material:check_email')->dailyAt('08:00');
        $schedule->command('attendance:violation')->weekly()->mondays()->at('08:40');

        $upload_transaction = db::connection('ympimis_2')
        ->table('ymes_interface_settings')
        ->where('interface', 1)
        ->where('remark', '=', 'transaction')
        ->first();

        $upload_error = db::connection('ympimis_2')
        ->table('ymes_interface_settings')
        ->where('interface', 1)
        ->where('remark', '=', 'error')
        ->first();

        if ($upload_transaction) {
            $interface = true;
            // Sabtu
            if (date('w') == 6) {
                if (intval(date('H')) >= 21) {
                    $interface = false;
                }
            }

            // Minggu
            if (date('w') == 0) {
                if (intval(date('H')) <= 12) {
                    $interface = false;
                }
            }

            if ($interface) {
                if ($upload_transaction->interface_frequency == '7PM') {
                    $schedule->command('ymes:interface')->dailyAt('07:00');
                } else if ($upload_transaction->interface_frequency == '8AM 11AM 3PM 8PM') {
                    $schedule->command('ymes:interface')->dailyAt('08:00');
                    $schedule->command('ymes:interface')->dailyAt('11:00');
                    $schedule->command('ymes:interface')->dailyAt('15:00');
                    $schedule->command('ymes:interface')->dailyAt('20:00');
                } else if ($upload_transaction->interface_frequency == 'Every Thirty Minutes') {
                    $schedule->command('ymes:interface')->everyThirtyMinutes();
                } else if ($upload_transaction->interface_frequency == 'Every Hour') {
                    $schedule->command('ymes:interface')->hourly();
                }
            }

        }

        if ($upload_error) {
            if ($upload_error->interface_frequency == '5AM') {
                $schedule->command('ymes:error_interface')->dailyAt('04:50');
                $schedule->command('ymes:error')->dailyAt('05:00');
            }
            if ($upload_error->interface_frequency == '8AM 11AM 3PM 8PM') {
                $schedule->command('ymes:error_interface')->dailyAt('07:50');
                $schedule->command('ymes:error')->dailyAt('08:00');
                $schedule->command('ymes:error_interface')->dailyAt('10:50');
                $schedule->command('ymes:error')->dailyAt('11:00');
                $schedule->command('ymes:error_interface')->dailyAt('14:50');
                $schedule->command('ymes:error')->dailyAt('15:00');
                $schedule->command('ymes:error_interface')->dailyAt('19:50');
                $schedule->command('ymes:error')->dailyAt('20:00');
            }
        }

        $schedule->command('ymes:sync')->dailyAt('05:30');
        $schedule->command('ymes:unmatch')->dailyAt('04:00');
        $schedule->command('report:sales')->dailyAt('06:00');
        $schedule->command('reminder:extra_order')->dailyAt('07:25');

        // $batch_flos = BatchSetting::where('remark', '=', 'FLO')->get();
        $batch_plan_stamps = BatchSetting::where('remark', '=', 'PLANSTAMP')->get();
        $batch_kd_shipments = BatchSetting::where('remark', '=', 'KDSHIPMENT')->get();

        // foreach ($batch_flos as $batch_flo) {
        //     if ($batch_flo->upload == 1) {
        //         $schedule->command('upload:completions')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //         $schedule->command('upload:transfers')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //         $schedule->command('upload:completionKD')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //         $schedule->command('upload:transferKD')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //         $schedule->command('upload:completionkitto')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //         $schedule->command('upload:transferkitto')->dailyAt(date('H:i', strtotime($batch_flo->batch_time)));
        //     }
        // }

        foreach ($batch_plan_stamps as $batch_plan_stamp) {
            if ($batch_plan_stamp->upload == 1) {
                $schedule->command('plan:stamps')->dailyAt(date('H:i', strtotime($batch_plan_stamp->batch_time)));
            }
        }

        foreach ($batch_kd_shipments as $batch_kd_shipment) {
            if ($batch_kd_shipment->upload == 1) {
                $schedule->command('kd:shipment')->everyTenMinutes();
            }
        }

        // $schedule->command('check:pianica')->dailyAt('10:31');
        // $schedule->command('check:pianica')->dailyAt('14:01');
        // $schedule->command('check:pianica')->dailyAt('19:01');

        $schedule->command('general:monthly')->monthlyOn(1, '06:00');
        $schedule->command('reminder:locker')->weekly()->mondays()->at('06:00');
        // $schedule->command('plan:leaves')->monthlyOn(1, '01:00');

        $schedule->command('reminder:license')->dailyAt('05:30');
        $schedule->command('record:daily_stocks')->dailyAt('07:00');
        $schedule->command('record:stock_middle')->dailyAt('07:00');
        $schedule->command('record:stock_middle')->dailyAt('16:00');

        $schedule->command('email:shipment')->weekdays()->dailyAt('08:40');
        $schedule->command('email:shipment')->weekends()->dailyAt('13:00');

        $schedule->command('reminder:room_temperature')->dailyAt('09:00');
        $schedule->command('reminder:room_temperature')->dailyAt('12:00');
        $schedule->command('reminder:room_temperature')->dailyAt('15:00');

        // $schedule->command('reminder:delivery_equipment')->dailyAt('00:05');
        // $schedule->command('reminder:delivery_canteen')->dailyAt('00:15');
        $schedule->command('reminder:penerimaan_barang')->dailyAt('22:00');

        // $schedule->command('qa:certificate')->dailyAt('01:15');
        // $schedule->command('qa:certificate')->dailyAt('06:30');
        $schedule->command('qa:certificate')->dailyAt('04:30');

        // $schedule->command('email:overtime')->weekends()->dailyAt('13:02');
        $schedule->command('email:middle_kanban')->weekdays()->dailyAt('07:15');
        $schedule->command('email:visitor_confirmation')->weekdays()->dailyAt('04:00');
        $schedule->command('injection:visual_check')->weekdays()->dailyAt('04:15');

        $schedule->command('reminder:audit_claim')->weekdays()->dailyAt('05:00');
        // $schedule->command('reminder:car_document')->weekly()->mondays()->at('04:00');
        $schedule->command('reminder:patrol')->weekly()->mondays()->at('04:00');
        // $schedule->command('email:visitor_confirmation')->everyMinute();
        // $schedule->command('email:confirmation_overtime')->weekdays()->dailyAt('06:55');

        // $schedule->command('plan:injections')->weekdays()->dailyAt('08:40');

        // $schedule->command('sync:ympicoid')->dailyAt('05:00');

        $schedule->command('log:room_temperature')->everyThirtyMinutes();
        // $schedule->command('log:power_monitor')->hourlyAt(59);
        // $schedule->command('log:survey_covid')->weekly()->saturdays()->at('12:00');
        // $schedule->command('skill_map:reminder')->weekly()->mondays()->at('06:15');
        // $schedule->command('highest:survey_covid')->weekly()->sundays()->at('18:30');

        // $schedule->command('log:survey_covid')->weekends()->dailyAt('21:00');
        // $schedule->command('notif:machine')->dailyAt('07:00');
        // $schedule->command('email:kaizen')->everyMinute();

        $schedule->command('email:user_document')->weekdays()->dailyAt('05:00');
        // $schedule->command('email:clinic_visit')->weekdays()->dailyAt('08:00');

        $schedule->command('update:address')->dailyAt('08:00');
        $schedule->command('update:address')->dailyAt('18:00');

        // $schedule->command('scheduling:chemical')->dailyAt('07:20');
        // $schedule->command('scheduling:chemical')->dailyAt('13:20');
        // $schedule->command('scheduling:chemical')->dailyAt('16:20');
        // $schedule->command('scheduling:chemical')->dailyAt('21:20');
        // $schedule->command('email:controlling_chart')->dailyAt('06:00');
        // $schedule->command('email:chemical_unpicked')->dailyAt('06:00');

        // $schedule->command('email:safety_riding')->monthlyOn(1, '07:00');
        // $schedule->command('skill:unfulfilled_log')->dailyAt('01:00');
        // $schedule->command('injection:schedule')->monthlyOn(1, '04:00');
        $schedule->command('injection:schedule')->dailyAt('04:00');
       // $schedule->command('mcu:schedule')->weekdays()->dailyAt('06:30');
       // $schedule->command('mcu:reminder')->dailyAt('18:00');
        $schedule->command('interview:schedule')->monthlyOn(1, '02:00');

        $schedule->command('email:double_transaction')->dailyAt('08:00');

        $schedule->command('generate:bom_scrap')->dailyAt('05:00');


        //RAW MATERIAL
        $schedule->command('generate:smbmr')->dailyAt('00:45');
        $schedule->command('email:raw_material_reminder')->dailyAt('10:20');
        // $schedule->command('email:raw_material_over')->dailyAt('11:00');

        // $schedule->command('raw_material:send_bc')->dailyAt('20:30');

        // $schedule->command('sync:plan_delivery')->hourly();

        // $batch_reminder_pos = BatchSetting::where('remark', '=', 'DELIVERYPO')->get();
        // foreach ($batch_reminder_pos as $batch_reminder_po) {
        //     if ($batch_reminder_po->upload == 1) {
        //         $schedule->command('raw_material:reminder_delivery')->dailyAt(date('H:i', strtotime($batch_reminder_po->batch_time)));
        //     }
        // }

        $schedule->command('resume:buffing')->hourlyAt(10);
        $schedule->command('resume:lacquering')->hourlyAt(10);
        $schedule->command('resume:plating')->hourlyAt(10);

        //Warehouse
        // $schedule->command('update:operator_internal')->dailyAt('07:00');
        // $schedule->command('update:operator_internal')->dailyAt('16:00');
        // $schedule->command('update:operator_internal')->dailyAt('16:00');

        $schedule->command('sync:greatday_attendance')->dailyAt('07:00');
        // $schedule->command('update:geocode')->dailyAt('18:00');
        // $schedule->command('update:geocode')->dailyAt('09:00');
        $schedule->command('sync:greatday_attendance')->dailyAt('17:00');
        // $schedule->command('update:geocode')->dailyAt('18:00');

        $schedule->command('sync:greatday_attendance')->dailyAt('19:00');
        $schedule->command('sync:greatday_attendance')->dailyAt('23:00');
        // $schedule->command('update:geocode')->dailyAt('23:30');

        $schedule->command('generate:live_cooking')->dailyAt('04:00');
        $schedule->command('generate:live_cooking')->dailyAt('10:10');
        $schedule->command('generate:live_cooking')->dailyAt('14:00');
        $schedule->command('generate:live_cooking')->dailyAt('19:00');

        $schedule->command('logs:updateWhInternal')->weekdays()->dailyAt('07:00');
        $schedule->command('logs:updateWhInternal')->weekdays()->dailyAt('16:00');
        // $schedule->command('logs:updateWhInternal')->weekdays()->dailyAt('17:00');
        $schedule->command('logs:updateWhInternal')->weekdays()->dailyAt('01:15');

        $schedule->command('general:minute')->everyMinute();

        // $schedule->command('m_app:schedule')->weekdays()->dailyAt('06:30');
        // $schedule->command('request_mp:reminder')->monthly();
        // $schedule->command('email:employee_end_date')->weekdays()->dailyAt('05:30');

        $schedule->command('general:data')->weekdays()->dailyAt('03:30');
        $schedule->command('reminder:sds')->dailyAt('04:30');

        // $schedule->command('automatic:general_affairs')->weekly()->mondays()->at('09:00');
        $schedule->command('automatic:general_affairs')->dailyAt('03:10');

        $schedule->command('reminder:birthday_employee')->dailyAt('03:10');
        $schedule->command('reminder:overtimeholidays')->weekdays()->dailyAt('05:15');
        $schedule->command('reminder:overtimeholidays')->weekdays()->dailyAt('09:00');
        $schedule->command('reminder:overtimeholidays')->weekdays()->dailyAt('12:00');
        $schedule->command('reminder:overtimeholidays')->weekdays()->dailyAt('14:00');

        // $schedule->command('reminder:scheduledownloadday')->dailyAt('00:30');
        $schedule->command('schedule:update_gs')->weekdays()->dailyAt('05:10');
        $schedule->command('schedule:update_gs')->weekdays()->dailyAt('16:00');
        $schedule->command('reminder:loading_limbah')->dailyAt('00:00');

    }

    /**l
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
