@extends('personnel.layouts.app')
@section('title', 'Mon Historique de Présence')
@section('page-title', 'Mes Pointages')

@section('content')
    <style>
        .at-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
        }

        .at-filter-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            align-items: flex-end;
        }

        .at-filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 160px;
        }

        .at-filter-group label {
            font-size: .75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .at-filter-input {
            padding: 9px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: .88rem;
            color: #374151;
            background: #F9FAFB;
            outline: none;
            transition: border-color .2s;
            width: 100%;
        }

        .at-filter-input:focus {
            border-color: #047857;
            background: #fff;
        }

        .at-filter-btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .18s;
            white-space: nowrap;
            text-decoration: none;
        }

        .at-filter-btn-primary {
            background: #047857;
            color: #fff;
        }

        .at-filter-btn-primary:hover {
            background: #065F46;
        }

        .at-filter-btn-reset {
            background: #F3F4F6;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
        }

        .at-filter-btn-reset:hover {
            background: #E5E7EB;
        }

        .at-active-filter {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ECFDF5;
            color: #047857;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .76rem;
            font-weight: 600;
        }

        .at-table-container {
            overflow-x: auto;
            margin-top: 15px;
            border-radius: 12px;
            border: 1px solid #F3F4F6;
        }

        .at-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .at-table th {
            text-align: left;
            padding: 12px 16px;
            background: #F9FAFB;
            color: #6B7280;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #F3F4F6;
        }

        .at-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #F3F4F6;
            color: #374151;
        }

        .at-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .at-badge-time {
            background: #ECFDF5;
            color: #059669;
        }

        @media (max-width: 640px) {
            .at-card {
                padding: 16px;
            }

            .at-filter-group {
                min-width: 100%;
            }

            .at-filter-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>

    <div class="at-card">
        <div
            style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <h2 style="font-size:1.1rem; font-weight:800; color:#111827;">Mon Journal de Présence</h2>
        </div>

        {{-- Filtres --}}
        <form method="GET" action="{{ request()->url() }}">
            <div class="at-filter-row">
                <div class="at-filter-group">
                    <label>Du</label>
                    <input type="date" name="date_from" class="at-filter-input" value="{{ $dateFrom ?? '' }}">
                </div>
                <div class="at-filter-group">
                    <label>Au</label>
                    <input type="date" name="date_to" class="at-filter-input" value="{{ $dateTo ?? '' }}">
                </div>
                <div style="display:flex;gap:8px;flex:1;min-width:240px;">
                    <button type="submit" class="at-filter-btn at-filter-btn-primary" style="flex:1;">Filtrer</button>
                    @if(($dateFrom ?? '') || ($dateTo ?? ''))
                        <a href="{{ request()->url() }}" class="at-filter-btn at-filter-btn-reset">✕</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Filtres actifs --}}
        @if(($dateFrom ?? '') || ($dateTo ?? ''))
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px;">
                @if($dateFrom ?? '')
                    <span class="at-active-filter">📅 Du {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d M Y') }}</span>
                @endif
                @if($dateTo ?? '')
                    <span class="at-active-filter">📅 Au {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d M Y') }}</span>
                @endif
            </div>
        @endif

        <div class="at-table-container">
            <table class="at-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">Date</th>
                        <th style="text-align:center;">Arrivée</th>
                        <th style="text-align:center;">Départ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $at)
                        <tr>
                            <td style="text-align:center;font-weight:600;color:#111827;">
                                {{ $at->date->translatedFormat('d M Y') }}
                            </td>
                            <td style="text-align:center;">
                                @if($at->clock_in)
                                    <span class="at-badge at-badge-time">
                                        {{ \Carbon\Carbon::parse($at->clock_in)->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color:#9CA3AF;">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                @if($at->clock_out)
                                    <span class="at-badge at-badge-time">
                                        {{ \Carbon\Carbon::parse($at->clock_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color:#9CA3AF;">Non renseigné</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;padding:40px;color:#9CA3AF;">
                                @if(($dateFrom ?? '') || ($dateTo ?? ''))
                                    Aucun pointage trouvé pour cette période.
                                @else
                                    Vous n'avez aucun pointage enregistré.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection