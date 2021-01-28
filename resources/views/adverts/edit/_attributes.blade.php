<form method="POST"
      action="{{ $editUser === 'admin' ? route('admin.adverts.adverts.update.attributes', $advert) : route('cabinet.adverts.update.attrubutes', $advert) }}">
    @csrf
    @method('PUT')
    <div class="card mb-3">
        <div class="card-header h3">Редактирование характеристик</div>
        <div class="card-body pb-2">
            @foreach ($advert->category->allAttributes() as $attribute)
                <div class="form-group">
                    <label for=attribute_{{ $attribute->id }}" class="col-form-label">{{ $attribute->name }}</label>
                    @if ($attribute->isSelect())
                        <select id="attribute_{{ $attribute->id }}"
                                class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                                name="attributes[{{ $attribute->id }}]">
                            <option value=""></option>
                            @foreach ($attribute->variants as $variant)
                                <option
                                    value="{{ $variant }}"{{ $variant == old('attributes.' . $attribute->id) || $variant == $advert->getAdvertAttributeValue($attribute) ? ' selected' : '' }}>
                                    {{ $variant }}
                                </option>
                            @endforeach
                        </select>
                    @elseif ($attribute->isInteger())
                        <input id="attribute_{{ $attribute->id }}" type="number"
                               class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                               name="attributes[{{ $attribute->id }}]"
                               value="{{ old('attributes.' . $attribute->id ?? $advert->getAdvertAttributeValue($attribute)) }}">
                    @elseif ($attribute->isString())
                        <input id="attribute_{{ $attribute->id }}" type="text"
                               class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                               name="attributes[{{ $attribute->id }}]"
                               value="{{ old('attributes.' . $attribute->id) ?? $advert->getAdvertAttributeValue($attribute) }}">
                    @else
                        <input type="hidden"
                               name="attributes[{{ $attribute->id }}]"
                               value="0">
                        <div class="checkbox d-inline">
                            <input id="attribute_{{ $attribute->id }}", type="checkbox", {{($advert->getAdvertAttributeValue($attribute) === 'да' ? 'checked' : '')}} , name="attributes[{{ $attribute->id }}]" {{ old('attributes.' . $attribute->id) ?? ($advert->getAdvertAttributeValue($attribute) === 'да' ? 'on' : 'off'), ($advert->getAdvertAttributeValue($attribute) === 'да' ? 'on' : 'off') }}>
                        </div>
                    @endif

                    @if ($errors->has('attributes.' . $attribute->id))
                        <span
                            class="invalid-feedback"><strong>{{ errorOutput($errors->first('attributes.' . $attribute->id)) }}</strong></span>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="form-group ml-4">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
