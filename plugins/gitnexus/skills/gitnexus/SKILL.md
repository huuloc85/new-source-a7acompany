---
name: gitnexus
description: Use when querying the local GitNexus MCP index for code search, symbol context, impact analysis, or change detection in this repository.
---

# GitNexus

Start with the indexed repo context:

```text
gitnexus://repo/be-a7acompany-2026/context
```

Use the MCP tools for graph-backed work:

- `list_repos` to confirm the indexed repository is registered.
- `query` to search execution flows and related code.
- `context` to inspect callers, callees, and process participation for a symbol.
- `impact` before editing a function, class, or method.
- `detect_changes` before committing.

If the index is stale, run:

```bash
npx gitnexus@1.6.4-rc.21 analyze --name be-a7acompany-2026
```
