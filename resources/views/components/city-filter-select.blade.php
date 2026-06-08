@props([
    'name' => 'city',
    'id' => 'city',
    'selected' => '',
    'districts' => collect(),
    'placeholder' => 'All cities',
])

<select class="input" id="{{ $id }}" name="{{ $name }}">
    <option value="">{{ $placeholder }}</option>
    @foreach($districts as $district)
        <optgroup label="{{ $district->name }}">
            <option value="{{ $district->name }}" {{ $selected === $district->name ? 'selected' : '' }}>
                All {{ $district->name }}
            </option>
            @foreach($district->children as $area)
                <option value="{{ $area->name }}" {{ $selected === $area->name ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
