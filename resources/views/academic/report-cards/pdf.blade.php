<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
 <title>Report Card</title>
 <style>
 * { margin: 0; padding: 0; box-sizing: border-box; }
 body {
 font-family: "DejaVu Sans", "Helvetica", Arial, sans-serif;
 font-size: 11px;
 color: #1a1a2e;
 padding: 20px;
 }
 .header {
 border-bottom: 3px solid #1e4fa3;
 padding-bottom: 12px;
 margin-bottom: 15px;
 }
 .school-name {
 font-size: 22px;
 font-weight: bold;
 color: #1e4fa3;
 text-align: center;
 margin-bottom: 3px;
 }
 .school-meta {
 text-align: center;
 font-size: 11px;
 color: #555;
 }
 .title {
 text-align: center;
 font-size: 14px;
 font-weight: bold;
 background: #1e4fa3;
 color: #fff;
 padding: 6px;
 margin-bottom: 15px;
 }
 .student-info {
 width: 100%;
 margin-bottom: 12px;
 border-collapse: collapse;
 }
 .student-info td {
 padding: 4px 8px;
 font-size: 11px;
 }
 .student-info td.label {
 font-weight: bold;
 color: #555;
 width: 20%;
 }
 table.grades {
 width: 100%;
 border-collapse: collapse;
 margin-top: 10px;
 }
 table.grades th {
 background: #1e4fa3;
 color: #fff;
 padding: 6px;
 text-align: left;
 font-size: 11px;
 border: 1px solid #1e4fa3;
 }
 table.grades td {
 padding: 5px 8px;
 border: 1px solid #d6dde8;
 font-size: 11px;
 }
 table.grades tr:nth-child(even) td {
 background: #f7f9fc;
 }
 .summary {
 margin-top: 15px;
 width: 100%;
 }
 .summary table {
 width: 100%;
 border-collapse: collapse;
 }
 .summary td {
 padding: 6px;
 border: 1px solid #d6dde8;
 font-size: 11px;
 }
 .summary td.label {
 font-weight: bold;
 background: #f0f4fa;
 }
 .comment {
 margin-top: 15px;
 border: 1px solid #1e4fa3;
 padding: 10px;
 font-size: 11px;
 background: #f7f9fc;
 }
 .footer {
 margin-top: 30px;
 padding-top: 10px;
 border-top: 2px solid #1e4fa3;
 text-align: center;
 font-size: 10px;
 color: #666;
 }
 .signatures {
 margin-top: 25px;
 width: 100%;
 }
 .signatures td {
 padding: 15px 8px;
 font-size: 11px;
 }
 .signatures .line {
 border-top: 1px solid #333;
 padding-top: 4px;
 font-size: 10px;
 color: #555;
 width: 180px;
 }
 </style>
</head>
<body>

 <div class="header">
 <div class="school-name">{{ $school?->name ?? "NEOVAM SCHOOL" }}</div>
 <div class="school-meta">
 {{ $school?->address ?? "" }}
 @if($school?->phone) | Tel: {{ $school->phone }} @endif
 @if($school?->email) | {{ $school->email }} @endif
 </div>
 </div>

 <div class="title">STUDENT REPORT CARD — {{ $term }} {{ $year }}</div>

 <table class="student-info">
 <tr>
 <td class="label">Student:</td>
 <td>{{ $student->full_name }}</td>
 <td class="label">Adm No:</td>
 <td>{{ $student->admission_no }}</td>
 </tr>
 <tr>
 <td class="label">Class:</td>
 <td>{{ $classroom ?? "-" }}</td>
 <td class="label">Level:</td>
 <td>{{ ucfirst($student->level) }}</td>
 </tr>
 <tr>
 <td class="label">Exam:</td>
 <td>{{ $exam->name }}</td>
 <td class="label">Position:</td>
 <td>
 @if($position)
 {{ $position }} out of {{ $class_size }}
 @else - @endif
 </td>
 </tr>
 </table>

 <table class="grades">
 <thead>
 <tr>
 <th style="width: 55%;">Subject</th>
 <th style="width: 20%;">Marks</th>
 <th style="width: 25%;">Grade</th>
 </tr>
 </thead>
 <tbody>
 @foreach($subjects as $row)
 <tr>
 <td>{{ $row["subject"] }}</td>
 <td>{{ $row["marks"] }}</td>
 <td>{{ $row["grade"] }}</td>
 </tr>
 @endforeach
 </tbody>
 </table>

 <div class="summary">
 <table>
 <tr>
 <td class="label" style="width: 25%;">Total Marks</td>
 <td style="width: 25%;">{{ $total }}</td>
 <td class="label" style="width: 25%;">Average</td>
 <td style="width: 25%;">{{ $average }}%</td>
 </tr>
 <tr>
 <td class="label">Overall Grade</td>
 <td><strong>{{ $grade }}</strong></td>
 <td class="label">Position</td>
 <td>
 @if($position) {{ $position }}/{{ $class_size }} @else - @endif
 </td>
 </tr>
 </table>
 </div>

 <div class="comment">
 <strong>Class Teacher's Comment:</strong><br>
 {{ $comment ?? "" }}
 </div>

 <table class="signatures">
 <tr>
 <td>
 <div class="line">Class Teacher</div>
 </td>
 <td>
 <div class="line">Academic Master</div>
 </td>
 <td>
 <div class="line">Head of School</div>
 </td>
 <td>
 <div class="line">Parent's Signature</div>
 </td>
 </tr>
 </table>

 <div class="footer">NEOVAM TECHNOLOGIES LTD — Connecting Schools. Informing Parents. Empowering Education.<br>Generated on {{ now()->format("d M Y H:i") }}
 </div>

</body>
</html>