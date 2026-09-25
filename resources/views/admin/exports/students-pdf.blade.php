<!DOCTYPE html><html><head><meta charset='UTF-8'><style>
 * { margin: 0; padding: 0; box-sizing: border-box; }
 body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; padding: 15px; }
 .header { border-bottom: 2px solid #1e4fa3; padding-bottom: 8px; margin-bottom: 12px; }
 .school-name { font-size: 16px; font-weight: bold; color: #1e4fa3; }
 .meta { font-size: 9px; color: #555; }
 h2 { font-size: 14px; color: #1e4fa3; text-align: center; margin: 10px 0; }
 table { width: 100%; border-collapse: collapse; }
 th { background: #1e4fa3; color: #fff; padding: 6px; text-align: left; font-size: 9px; border: 1px solid #1e4fa3; }
 td { padding: 5px 6px; border: 1px solid #d6dde8; font-size: 9px; }
 tr:nth-child(even) td { background: #f7f9fc; }
 .footer { margin-top: 15px; padding-top: 8px; border-top: 1px solid #1e4fa3; text-align: center; font-size: 8px; color: #666; }
</style></head><body>
<div class='header'>
 <div class='school-name'>{{ \->name ?? "NEOVAM SCHOOL" }}</div>
 <div class='meta'>{{ \->address }} | {{ \->phone }}</div>
</div>
<h2>STUDENTS LIST — {{ date("d M Y") }}</h2>
<table>
 <thead><tr><th>#</th><th>Adm No</th><th>Name</th><th>Gender</th><th>Level</th><th>Class</th><th>Parent</th><th>Parent Phone</th></tr></thead>
 <tbody>
 @foreach(\ as \ => \)
 <tr>
 <td>{{ \ + 1 }}</td>
 <td>{{ \->admission_no }}</td>
 <td>{{ \->full_name }}</td>
 <td>{{ ucfirst(\->gender) }}</td>
 <td>{{ ucfirst(\->level) }}</td>
 <td>{{ \->classroom?->name ?? "-" }}</td>
 <td>{{ \->parent_name }}</td>
 <td>{{ \->parent_phone }}</td>
 </tr>
 @endforeach
 </tbody>
</table>
<div class='footer'>Total: {{ \->count() }} students — NEOVAM TECHNOLOGIES LTD</div>
</body></html>