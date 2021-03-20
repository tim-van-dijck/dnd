

<style>
    #ability-scores {
        text-align: center;
        width: 60px;
    }
    #ability-scores ul {
        list-style-type: none;
        padding: 0;
        padding-top: 10px;
    }
    #ability-scores .bonus {font-size: 2em;}
    #ability-scores .score {margin-bottom: 30px;}

    #proficiency-bonus {
        margin-top: 3em;
        padding-left: 22px;
        text-align: left;
    }
    .selection-list {
        margin-left: 0;
        padding-left: 33px
    }
    .selection-list li {
        font-size: 13.5px; margin-left: 0; margin-bottom: .18px;
    }
    #saving-throws {margin-top: 21px;}
    #skill-proficiencies { margin-top: 42.5px; }
</style>

<table>
    <tr>
        <td id="ability-scores">
            <ul>
                @foreach($abilityScores as $score)
                    <li class="bonus">{{ floor(($score - 10) / 2) }}</li>
                    <li class="score">{{ $score }}</li>
                @endforeach
            </ul>
        </td>
        <td style="width: 155px;">
            <p id="proficiency-bonus">{{ $proficiencyBonus >= 0 ? '+' : '-' }}{{ $proficiencyBonus }}</p>
            <ul id="saving-throws" class="selection-list">
                @foreach (array_keys($abilityScores) as $ability)
                    <li @if(!in_array($ability, $savingThrows))style="visibility:hidden;"@endif>{{ $proficiencyBonus >= 0 ? '+' : '-' }}{{ $proficiencyBonus }}</li>
                @endforeach
            </ul>
            <ul id="skill-proficiencies" class="selection-list">
                @foreach ($skills as $name => $proficiency)
                    <li @if(!$proficiency)style="visibility:hidden;"@endif>{{ $proficiencyBonus >= 0 ? '+' : '-' }}{{ $proficiencyBonus }}</li>
                @endforeach
            </ul>
        </td>
    </tr>
</table>