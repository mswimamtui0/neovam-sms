<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            /* ============ ABSENCE ============ */
            ["absence", "en", "Absence Notification",
             "Dear Parent, your child {student_name} was not in school today ({date}). Please contact the school.",
             "Sent when a student is absent"],
            ["absence", "sw", "Taarifa ya Kutokuwepo",
             "Mpendwa Mzazi, mtoto wako {student_name} hakufika shule leo ({date}). Tafadhali wasiliana na shule.",
             "Hutumwa mtoto asipofika shule"],

            /* ============ RESULT ============ */
            ["result", "en", "Exam Results",
             "Dear Parent, results for {student_name} ({exam_name}) are ready. {summary}. Login to view full report.",
             "Sent when results are published"],
            ["result", "sw", "Matokeo ya Mtihani",
             "Mpendwa Mzazi, matokeo ya {student_name} ({exam_name}) yametolewa. {summary}. Ingia kwenye mfumo kuona ripoti kamili.",
             "Hutumwa matokeo yanapotolewa"],

            /* ============ PAYMENT ============ */
            ["payment", "en", "Payment Received",
             "Payment received: TZS {amount} for {student_name}. Receipt: {receipt}. Balance: TZS {balance}.",
             "Sent when a payment is recorded"],
            ["payment", "sw", "Malipo Yamepokelewa",
             "Malipo yamepokelewa: TZS {amount} kwa {student_name}. Risiti: {receipt}. Salio: TZS {balance}.",
             "Hutumwa malipo yanaporekodiwa"],

            /* ============ FEE REMINDER ============ */
            ["fee_reminder", "en", "Fee Reminder",
             "Reminder: Fees for {student_name} of TZS {balance} due on {due_date}. Please pay at the school office.",
             "Sent before/on/after fee due date"],
            ["fee_reminder", "sw", "Kumbusho la Ada",
             "Kumbusho: Ada ya {student_name} ya TZS {balance} inatakiwa kulipwa ifikapo {due_date}. Tafadhali lipa ofisi ya shule.",
             "Hutumwa kabla/muda/baada ya ada kulipwa"],

            /* ============ EMERGENCY ============ */
            ["emergency", "en", "Emergency Alert",
             "URGENT: {student_name} — {incident_type}. {description}. Please contact the school immediately.",
             "Sent on emergency/incident"],
            ["emergency", "sw", "Taarifa ya Dharura",
             "DHARURA: {student_name} — {incident_type}. {description}. Tafadhali wasiliana na shule mara moja.",
             "Hutumwa tukio la dharura linapotokea"],

            /* ============ WELCOME ============ */
            ["welcome", "en", "Welcome New Student",
             "Dear Parent, welcome to {school_name}. {student_name} ({admission_no}) has been admitted to {class_name} ({level}). Fees: TZS {fee}. You will receive updates via SMS.",
             "Sent on student admission"],
            ["welcome", "sw", "Karibu Mwanafunzi Mpya",
             "Mpendwa Mzazi, karibu {school_name}. {student_name} ({admission_no}) amepokelewa darasa la {class_name} ({level}). Ada: TZS {fee}. Utapokea taarifa kupitia SMS.",
             "Hutumwa mwanafunzi anapopokelewa"],

            /* ============ PROMOTION ============ */
            ["promotion", "en", "Promotion Notice",
             "Dear Parent, {student_name} has been promoted from {old_class} to {new_class}. Congratulations!",
             "Sent when a student is promoted"],
            ["promotion", "sw", "Taarifa ya Kupandishwa Darasa",
             "Mpendwa Mzazi, {student_name} amepandishwa darasa kutoka {old_class} kwenda {new_class}. Hongera!",
             "Hutumwa mwanafunzi anapopandishwa darasa"],

            /* ============ GRADUATION ============ */
            ["graduation", "en", "Graduation Notice",
             "Congratulations! {student_name} has graduated from {old_class}. We wish them success in the future.",
             "Sent when a student graduates"],
            ["graduation", "sw", "Taarifa ya Kuhitimu",
             "Hongera! {student_name} amehitimu kutoka {old_class}. Tunamtakia mafanikio mema.",
             "Hutumwa mwanafunzi anapohitimu"],

            /* ============ DUTY REMINDER ============ */
            ["duty_reminder", "en", "Duty Reminder",
             "Reminder: You are on duty on {date} ({shift} shift). Please report on time.",
             "Sent to teacher on duty"],
            ["duty_reminder", "sw", "Kumbusho la Wajibu",
             "Kumbusho: Una wajibu wa doria tarehe {date} (muda wa {shift}). Tafadhali ripoti kwa wakati.",
             "Hutumwa mwalimu wa doria"],

            /* ============ GENERAL ============ */
            ["announcement", "en", "School Announcement",
             "{message}",
             "General school announcement"],
            ["announcement", "sw", "Tangazo la Shule",
             "{message}",
             "Tangazo la jumla"],
        ];

        foreach ($templates as $t) {
            SmsTemplate::updateOrCreate(
                ["key" => $t[0], "language" => $t[1]],
                [
                    "name"        => $t[2],
                    "body"        => $t[3],
                    "description" => $t[4],
                    "is_active"   => true,
                ]
            );
        }

        $this->command->info("Seeded " . count($templates) . " SMS templates.");
    }
}