<?php

namespace App\Enums;

enum ExportJobStatus: string
{
  case Open = 'Open';
  case VehicleRequired = 'Vehicle Required';
  case OnRoute = 'On Route';
  case EmptyPick = 'Empty Pick';
  case ReadyToMove = 'Ready To Move';
  case ContainerReturned = 'Container Returned';
  case DryOff = 'Dry Off';
  case Completed = 'Completed';
  case Cancelled = 'Cancelled';

  /**
   * Define valid transitions for Empty and Loaded jobs
   */
  public static function transitions(string $jobType): array
  {
    return match ($jobType) {
      'Empty' => [
        self::Open->value => [self::VehicleRequired->value],
        self::VehicleRequired->value => [self::OnRoute->value],
        self::OnRoute->value => [self::EmptyPick->value],
        self::EmptyPick->value => [self::ReadyToMove->value, self::ContainerReturned->value],
        self::ReadyToMove->value => [self::VehicleRequired->value],
        self::ContainerReturned->value => [self::VehicleRequired->value],
        self::VehicleRequired->value => [self::OnRoute->value],
        self::OnRoute->value => [self::Completed->value, self::DryOff->value],
      ],

      'Loaded' => [
        self::ReadyToMove->value => [self::VehicleRequired->value],
        self::VehicleRequired->value => [self::OnRoute->value],
        self::OnRoute->value => [self::Completed->value, self::DryOff->value],
      ],

      default => [],
    };
  }
}
