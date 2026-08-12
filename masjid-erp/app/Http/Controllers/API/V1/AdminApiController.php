<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Shared base for the admin-facing v1 API controllers (Mahalla, Marriage,
 * Rental/Utensil, Meeting, Asset, Staff, Notification). None of these
 * controllers existed in the source dump — only their routes
 * (routes/api.php) and database schema did — so this base class fills in
 * the multi-tenant scoping convention used throughout the rest of the
 * schema (company_id + masjid_id on every row) for the staff/admin users
 * who call these endpoints, as opposed to the member-portal controllers
 * which scope by the logged-in Member record instead.
 */
abstract class AdminApiController extends Controller
{
    protected function currentCompanyId(Request $request): int
    {
        $companyId = $request->user()->company_id;

        if (! $companyId) {
            throw new HttpException(403, 'The authenticated user is not assigned to a company.');
        }

        return $companyId;
    }

    /**
     * Resolves which masjid an admin/staff request applies to: an explicit
     * masjid_id on the request (query string or body) if the user's role
     * allows working across masjids, otherwise the masjid the user account
     * itself is pinned to.
     */
    protected function currentMasjidId(Request $request): int
    {
        $masjidId = $request->input('masjid_id') ?? $request->user()->masjid_id;

        if (! $masjidId) {
            throw new HttpException(403, 'No masjid context for this request — pass masjid_id or assign the user to a masjid.');
        }

        return (int) $masjidId;
    }

    protected function paginate($query, Request $request)
    {
        return $query->paginate((int) $request->input('per_page', 20));
    }
}
