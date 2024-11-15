@if ($state === config('constants.RESERVATION_STATE_PENDING'))
  <select id="state" class="form-control" onchange="modChangeState({{ $id }}, this.value)">
    <option value="{{ config('constants.RESERVATION_STATE_PENDING') }}">
      {{ config('constants.RESERVATION_STATE_PENDING_LABEL') }}
    </option>
    <option value="{{ config('constants.RESERVATION_STATE_ACCEPTED') }}">
      {{ config('constants.RESERVATION_STATE_ACCEPTED_LABEL') }}
    </option>
    <option value="{{ config('constants.RESERVATION_STATE_REJECTED') }}">
      {{ config('constants.RESERVATION_STATE_REJECTED_LABEL') }}
    </option>
  </select>
@endif
