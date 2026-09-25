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
<h2>PAYMENTS REPORT: {{ \->format("d M Y") }} → {{ \->format("d M Y") }}</h2>
<table>
    <thead><tr><th>#</th><th>Receipt</th><th>Date</th><th>Student</th><th>Term</th><th>Amount</th><th>Method</th></tr></thead>
    <tbody>
    @foreach(\ as \ => \)
        <tr>
            <td>{{ \ + 1 }}</td>
            <td>{{ \->receipt_no }}</td>
            <td>{{ \->payment_date?->format("Y-m-d") }}</td>
            <td>{{ \->student?->full_name }}</td>
            <td>{{ \->invoice?->term }}</td>
            <td>{{ number_format(\->amount) }}</td>
            <td>{{ ucfirst(str_replace("_"," ",\->method)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class='footer'>Total: {{ number_format(\) }} TZS from {{ \->count() }} payments — NEOVAM TECHNOLOGIES LTD</div>
</body></html>