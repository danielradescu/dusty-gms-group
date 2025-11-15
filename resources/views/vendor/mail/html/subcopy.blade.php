<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td style="
            background-color:#f3f4f6;
            border-left:4px solid #4f46e5;
            padding:16px 20px;
            margin-top:20px;
            font-size:13px;
            line-height:1.6;
            color:#374151;
            border-radius:6px;
        ">
            {{ Illuminate\Mail\Markdown::parse($slot) }}
        </td>
    </tr>
</table>
