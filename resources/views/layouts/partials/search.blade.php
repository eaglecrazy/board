<div class="search-bar pt-3">
    <div class="container">
        <form
            action="{{ route('adverts.index', adPath(isset($currentRegion) ? $currentRegion : null, isset($currentCategory) ? $currentCategory : null)) }}"
            method="GET">
            <div>
                <div class="d-flex flex-fill flex-wrap">
                    <div class="form-group mr-3 flex-fill">
                        <input type="text" class="form-control" name="text" value="{{ request('text') }}"
                               placeholder="Поиск ...">
                    </div>
                    <div class="form-group mr-3">
                        <button id="main-search-button" class="btn btn-light border" type="submit"><span class="fa fa-search"></span></button>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-success"><span
                                class="fa fa-plus"></span> Добавить объявление</a>
                    </div>
                </div>
            </div>

            @if (isset($searchAttributes))
                <div class="pb-2 negative-margin">
                    <div class="d-flex flex-wrap">
                        @foreach ($searchAttributes as $attribute)
                            @if ($attribute->isSelect() || $attribute->isNumber())
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ $attribute->name }}</label>

                                        @if ($attribute->isSelect())
                                            <select class="form-control" name="attrs[{{ $attribute->id }}][equals]">
                                                <option value=""></option>
                                                @foreach ($attribute->variants as $variant)
                                                    <option
                                                        value="{{ $variant }}"{{ $variant === request()->input('attrs.' . $attribute->id . '.equals') ? ' selected' : '' }}>
                                                        {{ $variant }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{--   можно выбрать диапазон значений, от и до--}}
                                        @elseif ($attribute->isNumber())
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control"
                                                           name="attrs[{{ $attribute->id }}][from]"
                                                           value="{{ request()->input('attrs.' . $attribute->id . '.from') }}"
                                                           placeholder="From">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control"
                                                           name="attrs[{{ $attribute->id }}][to]"
                                                           value="{{ request()->input('attrs.' . $attribute->id . '.to') }}"
                                                           placeholder="To">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                            <div class="col-md-3">
                                <label class="col-form-label invisible">_</label>
                                <div class="d-flex">
                                    <button id="second-search-button" class="btn btn-primary flex-grow-1"><span class="fa fa-search"></span> Искать объявления</button>
                                </div>
                            </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
