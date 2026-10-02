## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2025-02-28 - Custom Checkboxes and Hidden Interactive Elements
**Learning:** Native `input[type="checkbox"]` elements styled with `display: none` cannot receive keyboard focus, completely breaking accessibility. Similarly, hiding interactive elements (like edit buttons) entirely until hover (`opacity-0 group-hover:opacity-100`) makes them undiscoverable for keyboard-only users.
**Action:** When styling custom checkboxes, use Tailwind's `sr-only` and `peer` on the input, and style the visual sibling using `peer-focus-visible`. For elements hidden until hovered, always add `focus-within:opacity-100` (and ensure the underlying interactive elements have `focus-visible` styling) so they can be focused and used via keyboard.
