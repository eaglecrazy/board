<form method="POST"
      action="{{ Auth::user()->can('manage-adverts') ? route('admin.adverts.adverts.update', $advert) : route('cabinet.adverts.update', $advert) }}">
    @csrf
    @method('PUT')
    <div class="card mb-3">
        <div class="card-header h3">Редактирование характеристик</div>
        <div class="card-body pb-2">
            @foreach ($advert->category->allAttributes() as $attribute)
                <div class="form-group">
                    <label for=attribute_{{ $attribute->id }}" class="col-form-label">{{ $attribute->name }}</label>

                    {{--SELECT--}}
                    @if ($attribute->isSelect())
                        <select id="attribute_{{ $attribute->id }}"
                                class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                                name="attributes[{{ $attribute->id }}]">
                            <option value=""></option>
                            @foreach ($attribute->variants as $variant)
                                <option
                                    value="{{ $variant }}"{{ $variant == old('attributes.' . $attribute->id) || $variant == $advert->getAdvertAttributeValue($attribute->id) ? ' selected' : '' }}>
                                    {{ $variant }}
                                </option>
                            @endforeach
                        </select>

                        {{--INTEGER--}}
                    @elseif ($attribute->isInteger())
                        <input id="attribute_{{ $attribute->id }}" type="number"
                               class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                               name="attributes[{{ $attribute->id }}]"
                               value="{{ old('attributes.' . $attribute->id ?? $advert->getAdvertAttributeValue($attribute->id)) }}">

                        {{--TEXT--}}
                    @else
                        <input id="attribute_{{ $attribute->id }}" type="text"
                               class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                               name="attributes[{{ $attribute->id }}]"
                               value="{{ old('attributes.' . $attribute->id) ?? $advert->getAdvertAttributeValue($attribute->id) }}">
                    @endif

                    @if ($errors->has('attributes.' . $attribute->id))
                        <span
                            class="invalid-feedback"><strong>{{ $errors->first('attributes.' . $attribute->id) }}</strong></span>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="form-group ml-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
