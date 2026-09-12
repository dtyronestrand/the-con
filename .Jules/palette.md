## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-14 - Accessible Custom Checkboxes
**Learning:** Using `display: none` on native `<input type="checkbox">` elements to style custom checkmarks completely removes them from the accessibility tree, making them invisible to keyboards and screen readers.
**Action:** Instead of `display: none`, use a visually-hidden CSS pattern (position absolute, 1px dimensions, clipped rect) to keep the input accessible, and style the custom checkmark using sibling selectors (like `input:focus-visible ~ span`) for focus rings.
