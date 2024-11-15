<span class="badge badge-dot mr-4">
  @php
    if ($state === config('constants.RESERVATION_STATE_PENDING')) {
      $color = 'bg-info';
      $label = config('constants.RESERVATION_STATE_PENDING_LABEL');
    } elseif ($state === config('constants.RESERVATION_STATE_ACCEPTED')) {
      $color = 'bg-success';
      $label = config('constants.RESERVATION_STATE_ACCEPTED_LABEL');
    } else {
      $color = 'bg-danger';
      $label = config('constants.RESERVATION_STATE_REJECTED_LABEL');
    }
  @endphp

  <i class="{{ $color }}"></i>
  <span class="status">{{ $label }}</span>
</span>
