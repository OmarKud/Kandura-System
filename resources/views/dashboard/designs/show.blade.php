@extends('layouts.dashboard')
@section('title','Design Details')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $locale = app()->getLocale();
    $name = data_get($design, "name.$locale") ?? $design->name ?? '—';
    $desc = data_get($design, "description.$locale") ?? $design->description ?? null;

    $imgUrl = function($img){
        if(!$img) return null;
        $path = $img->url ?? null;
        if(!$path) return null;

        if (Str::startsWith($path, ['http://','https://'])) return $path;
        return asset('storage/'.$path);
    };

    $images = $design->images ?? collect();
    $firstUrl = $imgUrl($images->first());

    $sizes = $design->measurements?->pluck('size')->filter()->unique()->values() ?? collect();
    $optionNames = $design->optionSelections?->pluck('designOption.name')->filter()->unique()->values() ?? collect();
@endphp

@section('content')
<style>
    .top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .top h2{margin:0;font-weight:1100;font-size:18px}

    .btn{
        height:40px;border-radius:12px;border:1px solid rgba(15,23,42,.14);
        background:#fff;padding:0 12px;font-weight:1000;cursor:pointer;
        text-decoration:none;display:inline-flex;align-items:center;gap:8px;color:inherit;
    }
    .btn.primary{background: rgba(37,99,235,.10);border-color: rgba(37,99,235,.22);color:#1d4ed8;}

    .wrap{display:grid;grid-template-columns: 1.35fr .65fr;gap:14px}
    @media(max-width: 980px){.wrap{grid-template-columns:1fr}}

    .box{
        background:#fff;border:1px solid rgba(15,23,42,.10);
        border-radius:18px;box-shadow: 0 10px 24px rgba(15,23,42,.06);
        overflow:hidden;
    }
    .box .pad{padding:12px}

    .hero{
        height: 420px;
        background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(212,160,23,.10));
        border-bottom:1px solid rgba(15,23,42,.10);
        position:relative;
    }
    .hero img{width:100%;height:100%;object-fit:cover;display:block}
    .hero.fit img{object-fit:contain;background:#fff}

    .hero-actions{
        position:absolute;top:12px;inset-inline-end:12px;display:flex;gap:10px;flex-wrap:wrap;
    }
    .pill{
        height:36px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(255,255,255,.92);padding:0 12px;font-weight:1000;cursor:pointer;
        display:inline-flex;align-items:center;gap:8px;
    }

    .thumbs{display:flex;gap:10px;flex-wrap:wrap;padding:12px}
    .thumb{
        width:88px;height:66px;border-radius:14px;overflow:hidden;cursor:pointer;
        border:1px solid rgba(15,23,42,.12);
        background: rgba(15,23,42,.03);
    }
    .thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .thumb.active{outline: 2px solid rgba(37,99,235,.55);}

    .title{font-weight:1100;margin:0 0 6px 0}
    .muted{color:var(--muted);font-weight:800;font-size:12.5px;line-height:1.6}

    .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
    .chip{
        padding: 7px 10px;border-radius:999px;border:1px solid rgba(15,23,42,.12);
        background: rgba(15,23,42,.03);font-size:12px;font-weight:1000;
        display:inline-flex;align-items:center;gap:6px;
    }
    .chip.blue{border-color: rgba(37,99,235,.25);background: rgba(37,99,235,.10);color:#1d4ed8;}
    .chip.gold{border-color: rgba(212,160,23,.25);background: rgba(212,160,23,.14);color:#7a5b00;}

    .kv{display:grid;grid-template-columns:120px 1fr;gap:8px;margin-top:10px}
    .kv b{font-size:12px}
</style>

<div class="top">
    <div>
        <h2>👁️ #{{ $design->id }} — {{ $name }}</h2>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn" href="{{ route('dashboard.designs.index') }}">⬅ Back</a>
    </div>
</div>

<div class="wrap">
    {{-- LEFT: Images --}}
    <div class="box">
        <div class="hero" id="heroBox">
            @if($firstUrl)
                <img id="heroImg" src="{{ $firstUrl }}" alt="design image">
            @else
                <div style="width:100%;height:100%;display:grid;place-items:center;font-weight:1100;color:#1d4ed8;">
                    No Image
                </div>
            @endif

            <div class="hero-actions">
                <button class="pill" type="button" onclick="toggleHeroFit()">🖼️ Fit</button>
            </div>
        </div>

        @if($images->count() > 1)
            <div class="thumbs">
                @foreach($images as $i => $img)
                    @php $u = $imgUrl($img); @endphp
                    <div class="thumb {{ $i===0 ? 'active' : '' }}" onclick="setHero('{{ $u }}', this)">
                        @if($u)
                            <img src="{{ $u }}" alt="thumb">
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="pad muted">عدد الصور: <b>{{ $images->count() }}</b></div>
        @endif

        <div class="pad">
            <h3 class="title">Description</h3>
            <div class="muted">{{ $desc ?: '—' }}</div>
        </div>
    </div>

    {{-- RIGHT: Details --}}
    <div class="box">
        <div class="pad">
            <h3 class="title">Details</h3>

            <div class="chips">
                <span class="chip">#{{ $design->id }}</span>
                <span class="chip gold">💰 {{ (float)$design->price }}</span>
                <span class="chip blue">👤 {{ $design->user->name ?? '—' }}</span>
                <span class="chip">🖼️ {{ $images->count() }}</span>
            </div>

            <div class="kv">
                <b>Created</b>
                <div class="muted">{{ optional($design->created_at)->toDayDateTimeString() }}</div>

                <b>Updated</b>
                <div class="muted">{{ optional($design->updated_at)->toDayDateTimeString() }}</div>
            </div>

            <div style="margin-top:12px;">
                <h3 class="title">Measurements (Sizes)</h3>
                <div class="chips">
                    @if($sizes->count())
                        @foreach($sizes as $s)
                            <span class="chip blue">📏 {{ $s }}</span>
                        @endforeach
                    @else
                        <span class="muted">—</span>
                    @endif
                </div>
            </div>

            <div style="margin-top:12px;">
                <h3 class="title">Available Options</h3>
                <div class="chips">
                    @if($optionNames->count())
                        @foreach($optionNames as $o)
                            <span class="chip">⚙️ {{ $o }}</span>
                        @endforeach
                    @else
                        <span class="muted">—</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setHero(url, el){
        if(!url) return;
        const heroImg = document.getElementById('heroImg');
        if(!heroImg) return;
        heroImg.src = url;

        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        if(el) el.classList.add('active');
    }

    function toggleHeroFit(){
        const hero = document.getElementById('heroBox');
        if(!hero) return;
        hero.classList.toggle('fit');
    }
</script>
@endsection
