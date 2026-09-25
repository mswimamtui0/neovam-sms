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
 /* ============ TIMETABLE CHANGE ============ */
 ["timetable_change", "en", "Timetable Update",
 "Dear {recipient_name}, the timetable for {class_name} has been updated ({day}, {period}). Please check.",
 "Sent on timetable change"],

 ["timetable_change", "sw", "Mabadiliko ya Ratiba",
 "Mpendwa {recipient_name}, ratiba ya {class_name} imebadilishwa ({day}, {period}). Tafadhali angalia.",
 "Hutumwa ratiba inapobadilishwa"],

 /* ============ ROLE CHANGE ============ */
 ["role_change", "en", "Role Updated",
 "Dear {staff_name}, your role has been updated to {new_role}. Login to see your new tools.",
 "Sent on role change"],

 ["role_change", "sw", "Wajibu Umebadilishwa",
 "Mpendwa {staff_name}, wajibu wako umebadilishwa kuwa {new_role}. Ingia kuona zana zako mpya.",
 "Hutumwa wajibu unapobadilishwa"],

 /* ============ LEAVE APPROVED ============ */
 ["leave_approved", "en", "Leave Approved",
 "Dear {staff_name}, your leave from {start_date} to {end_date} has been APPROVED.",
 "Sent when leave is approved"],

 ["leave_approved", "sw", "Likizo Imeidhinishwa",
 "Mpendwa {staff_name}, likizo yako kutoka {start_date} hadi {end_date} imeidhinishwa.",
 "Hutumwa likizo inapoidhinishwa"],

 /* ============ LEAVE REJECTED ============ */
 ["leave_rejected", "en", "Leave Rejected",
 "Dear {staff_name}, your leave request has been REJECTED. Reason: {reason}.",
 "Sent when leave is rejected"],

 ["leave_rejected", "sw", "Likizo Imekataliwa",
 "Mpendwa {staff_name}, ombi lako la likizo limekataliwa. Sababu: {reason}.",
 "Hutumwa likizo inapokataliwa"],

 /* ============ CLASS TEACHER CHANGE ============ */
 ["class_teacher_change", "en", "Class Teacher Change",
 "Dear {staff_name}, you have been {action} as Class Teacher of {class_name}.",
 "Sent on class teacher change"],

 ["class_teacher_change", "sw", "Mabadiliko ya Mwalimu wa Darasa",
 "Mpendwa {staff_name}, ume{action} kama Mwalimu wa Darasa la {class_name}.",
 "Hutumwa mwalimu wa darasa anapobadilishwa"],

 /* ============ LOGIN ALERT ============ */
 ["login_alert", "en", "Login Alert",
 "Dear {user_name}, a new login to your account was detected from {ip} at {time}. If this wasn't you, change your password immediately.",
 "Sent on new device login"],

 ["login_alert", "sw", "Taarifa ya Kuingia",
 "Mpendwa {user_name}, kuingia mpya kwenye akaunti yako kumegunduliwa kutoka {ip} saa {time}. Kama si wewe, badilisha neno la siri.",
 "Hutumwa kuingia mpya kunapogunduliwa"],

 /* ============ EXAM SCHEDULE ============ */
 ["exam_schedule_change", "en", "Exam Schedule Changed",
 "Dear Parent, exam schedule for {student_name} ({exam_name}) has changed. New: {new_date} at {new_time}.",
 "Sent on exam schedule change"],

 ["exam_schedule_change", "sw", "Ratiba ya Mtihani Imebadilishwa",
 "Mpendwa Mzazi, ratiba ya mtihani ya {student_name} ({exam_name}) imebadilishwa. Mpya: {new_date} saa {new_time}.",
 "Hutumwa ratiba ya mtihani inapobadilishwa"],

 /* ============ LIBRARY OVERDUE ============ */
 ["library_overdue", "en", "Library Book Overdue",
 "Dear Parent, the library book '{book_title}' borrowed by {student_name} was due on {due_date}. Please return it.",
 "Sent when book is overdue"],

 ["library_overdue", "sw", "Kitabu Kimechelewa",
 "Mpendwa Mzazi, kitabu '{book_title}' kilichokopwa na {student_name} kilipaswa kurudishwa {due_date}. Tafadhali kirudishe.",
 "Hutumwa kitabu kinapochelewa"],

 /* ============ DEPARTMENT CHANGE ============ */
 ["department_change", "en", "Department Updated",
 "Dear {staff_name}, your department has been updated to {department}. Login to see your new tools.",
 "Sent on department change"],

 ["department_change", "sw", "Idara Imebadilishwa",
 "Mpendwa {staff_name}, idara yako imebadilishwa kuwa {department}. Ingia kuona zana zako mpya.",
 "Hutumwa idara inapobadilishwa"],

 /* ============ PERFORMANCE REVIEW ============ */
 ["performance_review", "en", "Performance Review",
 "Dear {staff_name}, your {period} performance review is ready. Rating: {rating}/5. Login to see comments.",
 "Sent on performance review completion"],

 ["performance_review", "sw", "Tathmini ya Utendaji",
 "Mpendwa {staff_name}, tathmini yako ya {period} iko tayari. Kiwango: {rating}/5. Ingia kuona maoni.",
 "Hutumwa tathmini inapokamilika"],
 ];

 foreach ($templates as $t) {
 SmsTemplate::updateOrCreate(
 ["key" => $t[0], "language" => $t[1]],
 [
 "name" => $t[2],
 "body" => $t[3],
 "description" => $t[4],
 "is_active" => true,
 ]
 );
 }

 $this->command->info("Seeded " . count($templates) . " SMS templates.");
 }
}