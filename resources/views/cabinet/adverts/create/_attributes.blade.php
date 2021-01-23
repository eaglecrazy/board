@foreach ($category->allAttributes() as $attribute)

    <div class="form-group">

        <label for=attribute_{{ $attribute->id }}" class="col-form-label">{{ $attribute->name }}: </label>

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
        @elseif ($attribute->isString())
            <input id="attribute_{{ $attribute->id }}" type="text"
                   class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                   name="attributes[{{ $attribute->id }}]"
                   value="{{ old('attributes.' . $attribute->id) }}">
            {{--BOOL--}}
        @else
            <input type="hidden"
                   name="attributes[{{ $attribute->id }}]"
                   value="0">
            <div class="checkbox d-inline">
                    <input id="attribute_{{ $attribute->id }}", type="checkbox", name="attributes[{{ $attribute->id }}]" {{ old('attributes.' . $attribute->id) }}>
            </div>
        @endif



        @if ($errors->has('attributes.' . $attribute->id))
            <span class="invalid-feedback"><strong>{{ errorOutput($errors->first('attributes.' . $attribute->id)) }}</strong></span>
        @endif
    </div>
@endforeach
