<div class="page" style="">
    @include('pdf.character-sheet.info-header')

    <style>
        #ais {padding-left: 5px;}
        #ais td {
            margin: 0;
            border: 5px solid transparent;
            width: 55px;
            text-align: center;
            line-height: 42px;
            height: 50px;
            font-size: 20px;
        }
        #hp {
            padding-top: 20px;
            height: 140px;
            font-size: 9px;
            text-align: right;
            padding-right: 10px;
        }
        #hit-dice {background-color: rgba(255,0,0,.5);}
        #hit-dice td {
            height: 80px;
            width: 100px;
        }
        #attacks table td {
            background-color: rgba(255,0,0,.5);
            height: 200px;
        }
    </style>

    <table>
        <tr>
            <td>
                @component('pdf.character-sheet.partial.abilities-skills', ['abilityScores' => $abilityScores, 'level' => $level, 'proficiencyBonus' => $proficiencyBonus, 'savingThrows' => $savingThrows, 'skills' => $skills])
                @endcomponent
            </td>
            <td style="width: 220px;">
                <table>
                    <tr id="ais">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>{{ $character->race->speed }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><div id="hp">20</div></td>
                    </tr>
                    <tr id="hit-dice">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="attacks">
                        <td>
                            <table style="margin-top: 10px;">
                                <tr>
                                    <td style="width: 80px;">&nbsp;</td>
                                    <td style="width: 40px;">&nbsp;</td>
                                    <td style="width: 80px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td rowspan="3"></td>
        </tr>
        <tr>
            <td>
                <p style="margin-top: 5px;">
                    {{ 10 + floor(($abilityScores['WIS'] - 10) / 2) + ($skills['Perception'] ? $proficiencyBonus : 0) }}
                </p>
            </td>
            <td rowspan="2"></td>
        </tr>
    </table>
</div>