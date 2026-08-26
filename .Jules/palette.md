## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-24 - Custom Form Control Keyboard Accessibility
**Learning:** Custom checkboxes implementing a visually hidden native input for keyboard focus need special attention. When navigating via keyboard inside custom complex list items, hover-only states (`group-hover:opacity-100`) make interactive sub-elements invisible.
**Action:** Use `group-focus-within:opacity-100` alongside hover states so that when keyboard focus reaches the hidden native input inside a custom control (like a checkbox checkmark), the visual styling is revealed and focus rings (`:focus-visible`) indicate interactive elements properly to keyboard users.
