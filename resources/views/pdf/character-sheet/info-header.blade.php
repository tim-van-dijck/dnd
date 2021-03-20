<style>
    #info { margin-top: 35px; margin-bottom: 33.5px; }
    #info-table,#info-table table {width: 380px;}
    #info-table td {padding: 6px 8px;}
    #info-table tr:first-child td {padding-top: 0;}
    #info-table td:nth-child(1) {width: 122px;}
    #info-table td:nth-child(2) {width: 103px;}
</style>
<div id="info">
    <table>
        <tr>
            <td style="width: 280px;">
                <p style="padding: 5px 30px;">
                    {{ $character->name }}
                </p>
            </td>
            <td id="info-table">
                <table>
                    <tr>
                        <td>Ranger</td>
                        <td>Outlander</td>
                        <td>Jefke</td>
                    </tr>
                    <tr>
                        <td>{{ $character->race->name }}</td>
                        <td>{{ $character->alignment }}</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>