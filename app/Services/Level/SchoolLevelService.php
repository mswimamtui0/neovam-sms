<?php

namespace App\Services\Level;

use App\Models\School;

class SchoolLevelService
{
 public static function enabled(string $level): bool
 {
 $school = self::currentSchool();
 return $school ? $school->isLevelEnabled($level) : false;
 }

 public static function all(): array
 {
 return self::currentSchool()?->enabledLevels() ?? [];
 }

 public static function currentSchool(): ?School
 {
 return School::first();
 }

 public static function onlyEnabled(array $levels): array
 {
 return array_values(array_intersect($levels, self::all()));
 }

 /**
 * All class levels supported by the system, in order.
 */
 public static function allKnown(): array
 {
 return [
 'nursery',
 'kg',
 'pre_unit',
 'primary',
 'secondary',
 'alevel',
 ];
 }

 /**
 * Human-readable labels.
 */
 public static function label(string $level): string
 {
 return match ($level) {
 'nursery' => 'Nursery',
 'kg' => 'Kindergarten (KG)',
 'pre_unit' => 'Pre-Unit',
 'primary' => 'Primary',
 'secondary' => 'Secondary',
 'alevel' => 'A-Level',
 default => ucfirst($level),
 };
 }
}