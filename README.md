# group_everyone

Adds a virtual "Everyone" group to a Nextcloud instance. 

## Usage

Simply enable the app and the group will be created.

## Excluding guest accounts

By default every account is a member of the group, including the accounts created by the [Guests](https://github.com/nextcloud/guests) app.
To keep guest accounts out of the group, run:

```bash
occ group_everyone:exclude-guests --on
```

Use `--off` to include them again, or run the command without an option to show the current setting.
The change applies immediately: excluded guests lose access to anything shared with the "Everyone" group, and included guests get access to it.

## Restricting sharing to administrators

By default every user can share with the "Everyone" group.
To only allow administrators of the instance to do so, run:

```bash
occ group_everyone:restrict-sharing --on
```

Shares with the group created by other users are then rejected. Nextcloud shows them its generic
"Failed to create share." error, and logs the reason ("Only administrators can share with the "Everyone" group").
The group is also removed from the share recipient suggestions of other users (Files, and apps using
the same search such as Talk or Deck), so they no longer see it.
Use `--off` to allow every user again, or run the command without an option to show the current setting.
Existing shares are not affected, and the group stays visible in calendar and contact sharing.

## Hiding the group from sharing

To hide the "Everyone" group from sharing for all users, including administrators, run:

```bash
occ group_everyone:hide-from-sharing --on
```

The group is then no longer suggested as a share recipient, neither for files nor for calendars and address books.
Calendars and address books that are shared with the group are no longer visible to its members.
Existing file shares with the group keep working, and creating new ones is still possible by other means than the share dialog (e.g. the OCS API).
To reject those, also restrict sharing to administrators (see above).
Use `--off` to show the group again, or run the command without an option to show the current setting.
