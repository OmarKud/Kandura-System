@extends('layouts.dashboard')
@section('title','Designs')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $locale = app()->getLocale();

    $measurements = $measurements ?? collect();
    $designOptions = $designOptions ?? collect();

    $imgUrl = function($img){
        if(!$img) return null;
        $path = $img->url ?? null;
        if(!$path) return null;

        if (Str::startsWith($path, ['http://','https://'])) return $path;

        // ✅ stored as "designs/uuid.jpg"
        return asset('storage/'.$path);
    };
@endphp

@section('content')
<style>
    .page-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .page-top h2{margin:0;font-weight:1000;font-size:18px}
    .sub{color:var(--muted);font-weight:800;font-size:12.5px;margin-top:4px}

    .opt-groups{
  display:flex;
  flex-direction:column;
  gap:10px;
  width:100%;
}

.opt-group{
  padding-top:10px;
  border-top:1px dashed rgba(15,23,42,.18);
}

.opt-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:8px;
}

.opt-title{
  font-size:12px;
  font-weight:1000;
  color: var(--muted);
  letter-spacing:.2px;
}

.opt-items{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}

    .filters{
        background: rgba(15,23,42,.02);
        border: 1px solid rgba(15,23,42,.10);
        border-radius: 16px;
        padding: 12px;
        margin-bottom: 14px;
    }
    .filters .row{display:flex;flex-wrap:wrap;gap:10px;align-items:end}
    .field{display:flex;flex-direction:column;gap:6px;min-width:180px;flex:1}
    .field label{font-size:12px;color:var(--muted);font-weight:900}
    .input, .select{
        height: 40px;
        border-radius: 12px;
        border: 1px solid rgba(15,23,42,.14);
        padding: 0 12px;
        outline:none;
        background:#fff;
        font-weight:800;
    }
    .btn{
        height:40px;
        border-radius: 12px;
        border:1px solid rgba(15,23,42,.14);
        background:#fff;
        padding:0 12px;
        cursor:pointer;
        font-weight:1000;
    }
    .btn.primary{
        background: rgba(37,99,235,.10);
        border-color: rgba(37,99,235,.22);
        color:#1d4ed8;
    }

    /* ✅ Cards grid */
    .grid{
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    @media (max-width: 980px){
        .grid{grid-template-columns: 1fr;}
    }

    .card{
        border: 1px solid rgba(15,23,42,.10);
        border-radius: 18px;
        overflow:hidden;
        background:#fff;
        box-shadow: 0 10px 24px rgba(15,23,42,.06);
        display:flex;
        flex-direction:column;
        min-height: 100%;
    }

    .imgwrap{
        height: 210px;
        background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(212,160,23,.10));
        border-bottom: 1px solid rgba(15,23,42,.10);
        position:relative;
    }
    .imgwrap img{
        width:100%;
        height:100%;
        object-fit: cover; /* default */
        display:block;
    }
    .imgwrap.fit img{object-fit: contain; background:#fff;}

    .fit-btn{
        position:absolute;
        top:10px;
        inset-inline-end:10px;
        height:34px;
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,.14);
        background: rgba(255,255,255,.92);
        padding: 0 12px;
        font-weight:1000;
        cursor:pointer;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }

    .body{padding: 12px 12px 14px}
    .title{
        margin:0;
        font-weight:1100;
        font-size:15px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
    }
    .title a{color:inherit;text-decoration:none}
    .title a:hover{text-decoration:underline}

    .meta{margin-top:8px;display:flex;flex-wrap:wrap;gap:8px}
    .chip{
        padding: 7px 10px;
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,.12);
        background: rgba(15,23,42,.03);
        font-size: 12px;
        font-weight: 1000;
        color:#0f172a;
        display:inline-flex;align-items:center;gap:6px;
    }
    .chip.blue{border-color: rgba(37,99,235,.25);background: rgba(37,99,235,.10);color:#1d4ed8;}
    .chip.gold{border-color: rgba(212,160,23,.25);background: rgba(212,160,23,.14);color:#7a5b00;}

    .lists{margin-top:10px;display:flex;flex-direction:column;gap:8px}
    .line{display:flex;flex-wrap:wrap;gap:8px}
    .muted{color:var(--muted);font-weight:800;font-size:12.5px}

    .actions{margin-top:12px;display:flex;gap:10px;flex-wrap:wrap}
    .a-btn{
        height: 40px;
        border-radius: 12px;
        border:1px solid rgba(15,23,42,.14);
        background:#fff;
        padding:0 12px;
        font-weight:1000;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
        color:inherit;
    }
    .a-btn.primary{background: rgba(37,99,235,.10);border-color: rgba(37,99,235,.22);color:#1d4ed8;}
</style>

<div class="page-top">
    <div>
        <h2>🎨 Designs</h2>
    </div>
</div>

{{-- ✅ Keep your filters (شكل أكبر) --}}
<div class="filters">
    <form method="GET" action="{{ route('dashboard.designs.index') }}">
        <div class="row">
            <div class="field" style="min-width:260px;flex:2">
                <label>Search</label>
                <input class="input" name="search" value="{{ request('search') }}" placeholder="name / description / user...">
            </div>

            <div class="field">
                <label>User name</label>
                <input class="input" name="user_name" value="{{ request('user_name') }}" placeholder="Ali...">
            </div>

            <div class="field">
                <label>Measurement</label>
                <select class="select" name="measurement_id">
                    <option value="">All</option>
                    @foreach($measurements as $m)
                        <option value="{{ $m->id }}" @selected(request('measurement_id') == $m->id)>
                            #{{ $m->id }} - {{ $m->size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Design option</label>
                <select class="select" name="design_option_id">
                    <option value="">All</option>
                    @foreach($designOptions as $opt)
                        <option value="{{ $opt->id }}" @selected(request('design_option_id') == $opt->id)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Min price</label>
                <input class="input" name="min_price" value="{{ request('min_price') }}" type="number" step="0.01">
            </div>

            <div class="field">
                <label>Max price</label>
                <input class="input" name="max_price" value="{{ request('max_price') }}" type="number" step="0.01">
            </div>

            <button class="btn primary" type="submit">Apply</button>
            <a class="btn" href="{{ route('dashboard.designs.index') }}" style="text-decoration:none;display:inline-flex;align-items:center">Reset</a>
        </div>
    </form>
</div>

<div class="grid">
    @forelse($designs as $d)
        @php
            $name = data_get($d, "name.$locale") ?? $d->name ?? '—';
            $firstImg = $d->images->first();
            $url = $imgUrl($firstImg);

            $sizes = $d->measurements?->pluck('size')->filter()->unique()->values() ?? collect();
$optionsByType = $d->optionSelections
    ?->filter(fn($s) => $s->designOption)
    ->groupBy(fn($s) => $s->designOption->type ?? 'other') ?? collect();
    $typeLabel = [
  'collar' => 'Collar',
  'sleeve' => 'Sleeve',
  'pocket' => 'Pocket',
  'fabric' => 'Fabric',
];

        @endphp

        <div class="card">
            <div class="imgwrap" data-fit="0">
                @if($url)
                    <img src="{{ $url }}" alt="design image">
                @else
                    <div style="width:100%;height:100%;display:grid;place-items:center;font-weight:1100;color:#1d4ed8;">
                        No Image
                    </div>
                @endif

                <button class="fit-btn" type="button" onclick="toggleFit(this)">
                    🖼️ Fit
                </button>
            </div>

            <div class="body">
                <div class="title">
                    <a href="{{ route('dashboard.designs.show', $d->id) }}">#{{ $d->id }} — {{ $name }}</a>
                    <span class="chip gold">💰 {{ (float)$d->price }}</span>
                </div>

                <div class="meta">
                    <span class="chip blue">👤 {{ $d->user->name ?? '—' }}</span>
                    <span class="chip">🖼️ {{ $d->images?->count() ?? 0 }}</span>
                </div>

                <div class="lists">
                    <div class="line">
                        <span class="muted">Sizes:</span>
                        @if($sizes->count())
                            @foreach($sizes as $s)
                                <span class="chip blue">📏 {{ $s }}</span>
                            @endforeach
                        @else
                            <span class="muted">—</span>
                        @endif
                    </div>

                    <div class="line">
  <span class="muted">Options:</span>

  @if($optionsByType->count())
    <div class="opt-groups">
      @foreach($optionsByType as $type => $rows)
        <div class="opt-group">
          <div class="opt-head">
            <span class="opt-title">{{ $typeLabel[$type] ?? ucfirst($type) }}</span>
          </div>

          <div class="opt-items">
            @foreach($rows->pluck('designOption.name')->filter()->unique()->values() as $name)
              <span class="chip">⚙️ {{ $name }}</span>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  @else
    <span class="muted">—</span>
  @endif
</div>

                </div>

                <div class="actions">
                    <a class="a-btn primary" href="{{ route('dashboard.designs.show', $d->id) }}">👁️ View</a>
                </div>
            </div>
        </div>
    @empty
        <div class="muted">لا يوجد Designs.</div>
    @endforelse
</div>

<div style="margin-top:14px;display:flex;justify-content:center;">
    {{ $designs->links() }}
</div>

<script>
    function toggleFit(btn){
        const wrap = btn.closest('.imgwrap');
        if(!wrap) return;
        wrap.classList.toggle('fit');
    }
</script>
@endsection
