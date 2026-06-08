<?php

namespace App\Models\Security\Logs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $username
 * @property int|null $user_id
 * @property string $ip_address
 * @property string|null $user_agent_encrypted
 * @property int $successful
 * @property string|null $reason
 * @property string|null $country
 * @property string|null $region
 * @property string|null $city
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $timezone
 * @property string|null $device_fingerprint
 * @property string|null $request_method
 * @property string|null $request_path
 * @property string|null $request_headers_encrypted
 * @property string|null $session_id
 * @property string|null $processing_time_ms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereDeviceFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereProcessingTimeMs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereRequestHeadersEncrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereRequestMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereRequestPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereUserAgentEncrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationAuditLog whereUsername($value)
 *
 * @mixin \Eloquent
 */
class AuthenticationAuditLog extends Model
{
    protected $table = 'authentication_audit_logs';
}
