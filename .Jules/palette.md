## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2025-03-05 - Keyboard Accessibility in Interactive Elements
**Learning:** Found a pattern in the Todolist where core interactions (like opening a task modal or adding/removing subtasks) used non-semantic elements (`<p role="button">`) without keyboard event handlers, or icon-only buttons without accessible names (`aria-label`). Additionally, interactive elements revealed on hover were missing `focus-within` states for keyboard navigation.
**Action:** When working on interactive elements, always ensure `tabindex="0"` and keyboard handlers (`@keydown.enter`, `@keydown.space.prevent`) are present if not using a native `<button>`. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons. Add `focus-within:opacity-100` alongside `group-hover:opacity-100`.
