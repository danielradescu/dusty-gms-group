<?php

namespace App\Traits;

use App\Enums\Role;

trait HasRoles
{
    // === PUBLIC: STRICT ROLE MATCHING ===

    public function isAdmin(): bool
    {
        return $this->isExactRole(Role::Admin);
    }

    public function isOrganizer(): bool
    {
        return $this->isExactRole(Role::Organizer);
    }

    public function isParticipant(): bool
    {
        return $this->isExactRole(Role::Participant);
    }

    // === PUBLIC: PERMISSION-HIERARCHY CHECKS ===

    public function hasAdminPermission(): bool
    {
        return $this->hasRolePermission(Role::Admin);
    }

    public function hasOrganizerPermission(): bool
    {
        return $this->hasRolePermission(Role::Organizer);
    }

    public function hasParticipantPermission(): bool
    {
        return $this->hasRolePermission(Role::Participant);
    }

    // === PRIVATE HELPERS ===

    private function isExactRole(Role $role): bool
    {
        return $this->role === $role;
    }

    private function hasRolePermission(Role $required): bool
    {
        return match ($required) {
            Role::Admin => $this->role === Role::Admin,
            Role::Organizer => in_array($this->role, [Role::Admin, Role::Organizer], true),
            Role::Participant => in_array($this->role, [Role::Admin, Role::Organizer, Role::Participant], true),
        };
    }

    public function scopeWithRoles($query, array $roles): void
    {
        $query->whereIn('role', array_map(fn ($role) => $role->value, $roles));
    }

    public function scopeOrganizers($query)
    {
        return $query->whereIn('role', [
            Role::Admin->value,
            Role::Organizer->value,
        ]);
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', [
            Role::Admin->value,
        ]);
    }
}
