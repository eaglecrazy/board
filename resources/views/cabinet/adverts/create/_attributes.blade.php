@foreach ($category->allAttributes() as $attribute)

    <div class="form-group">
        <label for=attribute_{{ $attribute->id }}" class="col-form-label">{{ 'id = ' . $attribute->id . ', name = ' . $attribute->name }}</label>

        {{--SELECT--}}
        @if ($attribute->isSelect())
            <select id="attribute_{{ $attribute->id }}"
                    class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                    name="attributes[{{ $attribute->id }}]">
                <option value=""></option>
                @foreach ($attribute->variants as $variant)
                    <option
                        value="{{ $variant }}"{{ $variant == old('attributes.' . $attribute->id) ? ' selected' : '' }}>
                        {{ $variant }}
                    </option>
                @endforeach
            </select>

        {{--INTEGER--}}
        @elseif ($attribute->isInteger())
            <input id="attribute_{{ $attribute->id }}" type="number"
                   class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                   name="attributes[{{ $attribute->id }}]"
                   value="{{ old('attributes.' . $attribute->id) }}">

        {{--TEXT--}}
        @else
            <input id="attribute_{{ $attribute->id }}" type="text"
                   class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                   name="attributes[{{ $attribute->id }}]"
                   value="{{ old('attributes.' . $attribute->id) }}">
        @endif



        @if ($errors->has('attributes.' . $attribute->id))
            <span class="invalid-feedback"><strong>{{ $errors->first('attributes.' . $attribute->id) }}</strong></span>
        @endif
    </div>
@endforeach
