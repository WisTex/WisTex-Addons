# AI Agent Runtime (`aiagent`)

An experimental Hubzilla addon intended to host configured AI agents using Hubzilla channel identities. The initial release verifies addon discovery and routing; **it is not yet an operational AI agent**.

## Installation

Place this directory at `addon/aiagent/` within a Hubzilla installation that loads addon collections, then enable **AI Agent Runtime** in the Hubzilla addon administration interface. When logged in to a local channel, visit `/aiagent`.

This directory belongs at the **root of a Hubzilla addon collection repository** such as `WisTex-Addons`. Do not install the collection as a single addon.

## What version 0.1.0 does

- Provides Hubzilla addon metadata and load/unload callbacks.
- Registers a read-only status route at `/aiagent`.
- Restricts the useful status response to locally authenticated channels.

It does **not** yet provide model API calls, configuration, scheduling, persistence, task actions, or messaging.

## Review and assumptions

- The host's Hubzilla version supports `Zotlabs\\Extend\\Route::register()` and `unregister()` with the demonstrated path and route signatures.
- The host's router resolves a registered `Zotlabs\\Module\\Aiagent` controller from `Mod_Aiagent.php` as in the inspected Neuhub addon example.
- The site's addon installer scans direct child folders of the configured addon collection.
- `local_channel()` is available to the controller at request time.

These assumptions are grounded in inspected Neuhub addon examples but **not yet tested on the target server**. The current endpoint is not an API and is not intended for remote or unauthenticated execution.

## Planned next increments

1. Confirm installation, enable/disable, and route resolution in the target Hubzilla version.
2. Add channel-specific configuration and explicit authorization rules.
3. Add a minimal provider adapter and manual invocation with bounded execution.
4. Add run records, scheduled execution, and initial planner adapters.

See `WisTex-Strategy/platforms/ai-agents/` for the operating model and runtime architecture.
