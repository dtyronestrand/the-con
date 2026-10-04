## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-14 - Custom Checkbox Keyboard Accessibility
**Learning:** Native `<input>` elements using `display: none` cannot receive keyboard focus, completely breaking keyboard accessibility for custom styled checkboxes.
**Action:** When building custom styled checkboxes or radios, hide the native input visually using Tailwind's `sr-only` utility, ensure it remains in the document flow, and use the `peer` class (`peer-focus-visible:ring-*`) to style adjacent sibling elements based on the native input's focus state.

## 2024-05-14 - Revealing Hidden Interactive Elements
**Learning:** Using `group-hover:opacity-100` to reveal interactive elements (like edit buttons or checkboxes) creates a trap for keyboard users who cannot hover, rendering the actions inaccessible.
**Action:** Whenever `group-hover:opacity-100` is used to reveal an interactive element, always pair it with `group-focus-within:opacity-100` so the element becomes visible and navigable when focused via the keyboard.
