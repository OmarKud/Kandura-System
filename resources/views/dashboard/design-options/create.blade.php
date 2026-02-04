@extends('layouts.dashboard')

@section('title', 'Create Design Option')

@section('content')

  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:14px;">
    <div>
      <h2 style="margin:0;font-size:18px;font-weight:900;color:#0f172a;">Create Design Option</h2>
      <div style="margin-top:4px;color:#64748b;font-size:12px;">Add new option with name + type.</div>
    </div>

    <a href="{{ route('dashboard.design-options.index') }}"
      style="text-decoration:none;padding:9px 12px;border-radius:999px;border:1px solid rgba(15,23,42,.10);background:#fff;color:#0f172a;font-size:12px;">
      ← Back
    </a>
  </div>

  @if($errors->any())
    <div
      style="margin-bottom:12px;padding:10px 12px;border-radius:14px;border:1px solid rgba(239,68,68,.25);background:rgba(239,68,68,.08);color:#991b1b;font-size:13px;">
      <b>Validation errors:</b>
      <ul style="margin:8px 0 0 18px;">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('dashboard.design-options.store') }}"
    style="background:#fff;border:1px solid rgba(15,23,42,.10);border-radius:16px;padding:14px;box-shadow:0 14px 40px rgba(15,23,42,.06);">
    @csrf

    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <div style="flex:1;min-width:220px;">
        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:6px;">Name (EN)</label>
        <input name="name[en]" value="{{ old('name.en') }}"
          style="width:100%;padding:10px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);outline:none;"
          placeholder="e.g. Classic Sleeve">
      </div>

      <div style="flex:1;min-width:220px;">
        <label style="display:block;font-size:12px;color:#64748b;margin-bottom:6px;">Name (AR)</label>
        <input name="name[ar]" value="{{ old('name.ar') }}"
          style="width:100%;padding:10px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);outline:none;"
          placeholder="مثلاً: كم كلاسيك">
      </div>
    </div>

    <div style="margin-top:12px;">
      <label style="display:block;font-size:12px;color:#64748b;margin-bottom:6px;">Type</label>
      <select name="type"
        style="width:100%;padding:10px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);outline:none;">
        <option value="">-- choose type --</option>
        <option value="collar" {{ old('type', $designOption->type ?? '') == 'collar' ? 'selected' : '' }}>Collar</option>
        <option value="sleeve" {{ old('type', $designOption->type ?? '') == 'sleeve' ? 'selected' : '' }}>Sleeve</option>
        <option value="pocket" {{ old('type', $designOption->type ?? '') == 'pocket' ? 'selected' : '' }}>Pocket</option>
        <option value="fabric" {{ old('type', $designOption->type ?? '') == 'fabric' ? 'selected' : '' }}>Fabric</option>
      </select>
    </div>

    <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
      <button type="submit"
        style="padding:10px 14px;border-radius:999px;border:none;background:#2563eb;color:#fff;font-weight:700;font-size:12px;cursor:pointer;box-shadow:0 10px 20px rgba(37,99,235,.18);">
        Save
      </button>

      <a href="{{ route('dashboard.design-options.index') }}"
        style="text-decoration:none;padding:10px 14px;border-radius:999px;border:1px solid rgba(15,23,42,.10);background:#fff;color:#0f172a;font-weight:700;font-size:12px;">
        Cancel
      </a>
    </div>
  </form>

@endsection