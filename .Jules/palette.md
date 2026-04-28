## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-15 - Hiding Text Symbols in Action Buttons
**Learning:** When using visual text symbols (like `+`, `-`, `✕`) for icon-only buttons instead of actual icons (SVG/images), screen readers will announce the literal character names (e.g., "plus", "hyphen-minus") which can be confusing or redundant alongside the `aria-label`.
**Action:** When creating icon-only buttons using text characters, always wrap the character itself in an `<span aria-hidden="true">` element. Provide the accessible name using the `aria-label` attribute on the wrapping `<button>` element. Ensure the button itself has `focus-visible` states.
