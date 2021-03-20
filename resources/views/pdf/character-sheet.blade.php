<html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {font-family: sans-serif;}
            .page {
                page-break-after: always;
                z-index: 1;
                margin: 0 25px;
            }
            .page td {vertical-align: top};
        </style>
    </head>
    <body>
        <img style="width: 100%; position: absolute; top: 0; left: 0; z-index: 0;" src="{{ storage_path('app/pdf-assets/character-sheet-p-1-high.jpg') }}" alt="character-sheet-p-1" />
        @component('pdf.character-sheet.page-1', ['abilityScores' => $abilityScores, 'character' => $character, 'level' => $level, 'proficiencyBonus' => $proficiencyBonus, 'savingThrows' => $savingThrows, 'skills' => $skills])
        @endcomponent
    </body>
</html>