# FE Guide: Sync Employee To Attendance Device

## Context

Backend production is running on cloud hosting, so it cannot reach the attendance device by LAN IP:

```text
http://192.168.1.200
```

The FE user's computer is in the same company network as the attendance device, so the sync must run from a client or local service that has LAN access.

Current BE behavior:

1. `POST /api/employees` creates the employee in the application database.
2. BE does not sync the employee to the attendance device.
3. FE is responsible for triggering the attendance-device sync from a LAN-accessible environment.

## Attendance Device Endpoint

```http
POST http://192.168.1.200/ISAPI/AccessControl/UserInfo/Record?format=json
Content-Type: application/json
Authorization: Digest ...
```

The device uses Digest Auth. FE must use the device username and password configured for ISAPI access.

## Payload

```json
{
    "UserInfo": {
        "employeeNo": "1001",
        "name": "Nguyen Van A",
        "userType": "normal",
        "Valid": {
            "enable": true,
            "beginTime": "2026-01-01T00:00:00",
            "endTime": "2036-01-01T23:59:59",
            "timeType": "local"
        }
    }
}
```

Map fields from BE employee response:

```text
employeeNo = employee.id
name       = employee.name
userType   = "normal"
```

## Recommended FE Flow

```text
1. FE submits employee form to BE:
   POST https://a7atest.id.vn/api/employees

2. If BE returns 201, FE reads employee data from response.

3. FE syncs the employee to the attendance device from the LAN-accessible machine/service.

4. FE shows separate status:
   - Employee created successfully
   - Attendance device sync succeeded / failed
```

Do not block application employee creation on attendance-device sync. The device can be offline, unreachable, or reject requests independently from the main app database.

## Important Browser Constraints

Direct browser calls from the web app may fail even when the PC can open the device page:

1. The app is served over HTTPS, while the device is HTTP. Browser mixed-content rules may block the request.
2. The device may not send CORS headers for `https://a7atest.id.vn`.
3. Digest Auth from browser JavaScript is not always supported cleanly through `fetch` or `axios`.
4. Putting the device admin username/password in browser JavaScript exposes credentials to users.

Because of this, the safest FE-side architecture is usually not pure browser-to-device. Use one of the following options.

## Option A: Local LAN Proxy / Agent

Run a small service inside the company LAN. FE calls this service, and the service calls the attendance device.

```text
Browser FE -> Local LAN proxy -> 192.168.1.200 ISAPI
```

The proxy should:

1. Store device credentials outside browser code.
2. Add Digest Auth when calling the device.
3. Allow CORS only from trusted FE origins.
4. Return a simple success/error response to FE.

Example proxy API contract:

```http
POST http://localhost:8787/acs/users
Content-Type: application/json
```

Request:

```json
{
    "employeeNo": "1001",
    "name": "Nguyen Van A"
}
```

Response success:

```json
{
    "success": true,
    "message": "Employee synced to attendance device."
}
```

Response failure:

```json
{
    "success": false,
    "message": "Attendance device sync failed.",
    "details": "Connection timed out"
}
```

## Option B: Internal Web App Host

Host the FE or a sync endpoint on a server inside the same LAN as the attendance device.

```text
Browser FE -> Internal sync endpoint -> 192.168.1.200 ISAPI
```

This is appropriate if the company already has an internal server that can reach `192.168.1.200`.

## Option C: Direct Browser Call

Only use this if testing confirms all conditions are true:

1. Browser allows HTTP request from the current FE page.
2. Device allows CORS from the FE origin.
3. Digest Auth can be implemented reliably.
4. Exposing credentials is acceptable or the device provides a limited account.

This option is not recommended for production unless the security tradeoff is explicitly accepted.

## Error Handling For FE

Treat BE creation and attendance-device sync as two separate outcomes.

Example UI states:

```text
Employee created, attendance sync pending.
Employee created, attendance sync succeeded.
Employee created, attendance sync failed. Please retry from a company-network machine.
```

Recommended retry behavior:

1. Store failed sync attempts locally or in app state.
2. Provide a manual `Retry sync to attendance device` action.
3. Show the device error message for support users.

## Manual Test From A Company-Network Machine

Use this to verify the device accepts the payload before implementing FE integration.

Windows PowerShell example with `curl.exe`:

```powershell
curl.exe --digest -u "DEVICE_USER:DEVICE_PASSWORD" `
  -H "Content-Type: application/json" `
  -d '{"UserInfo":{"employeeNo":"1001","name":"Nguyen Van A","userType":"normal","Valid":{"enable":true,"beginTime":"2026-01-01T00:00:00","endTime":"2036-01-01T23:59:59","timeType":"local"}}}' `
  "http://192.168.1.200/ISAPI/AccessControl/UserInfo/Record?format=json"
```

Expected result: the device returns HTTP `200` or a successful ISAPI response body.

If this command works on a company-network machine but fails from cloud BE, the issue is confirmed as network routing, not payload.
