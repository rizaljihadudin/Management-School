<h1>{{ $record->name }}</h1>
<h3>Children :</h3>
<ul>
    @foreach ($record->subjects as $key => $subject)
        <li>
            <strong>{{ $subject['label'] }}</strong>

            @if (!empty($subject['children']))
                <ul>
                    @foreach ($subject['children'] as $childKey => $child)
                        <li>{{ $child['label'] }}</li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
