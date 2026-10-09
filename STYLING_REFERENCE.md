# Blood Donation Platform - Styling Guide

## Quick Reference for Developers

### Color System

#### Backgrounds

```html
<!-- Main background -->
<body class="bg-gray-950">
  <!-- Card backgrounds -->
  <div class="bg-gray-800 border border-gray-700">
    <!-- Darker sections -->
    <div class="bg-gray-900"></div>
  </div>
</body>
```

#### Text Colors

```html
<!-- Primary text -->
<h1 class="text-white">
  <!-- Secondary text -->
  <p class="text-gray-400">
    <!-- Muted text -->
    <span class="text-gray-500"></span>
  </p>
</h1>
```

#### Status Colors

```html
<!-- Success -->
<span class="bg-green-900/30 text-green-400 border-green-700">
  <!-- Error -->
  <span class="bg-red-900/30 text-red-400 border-red-700">
    <!-- Warning -->
    <span class="bg-yellow-900/30 text-yellow-400 border-yellow-700">
      <!-- Info -->
      <span
        class="bg-blue-900/30 text-blue-400 border-blue-700"
      ></span></span></span
></span>
```

### Components

#### Cards

```html
<div
  class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200"
>
  <!-- Content -->
</div>
```

#### Buttons

**Primary Button**

```html
<button
  class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 font-semibold"
>
  Button Text
</button>
```

**Secondary Button**

```html
<button
  class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-semibold"
>
  Button Text
</button>
```

**Success Button**

```html
<button
  class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
>
  Approve
</button>
```

**Danger Button**

```html
<button
  class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
>
  Reject
</button>
```

#### Form Inputs

```html
<input
  type="text"
  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors"
  placeholder="Enter text"
/>
```

#### Select Dropdown

```html
<select
  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors"
>
  <option>Option 1</option>
</select>
```

#### Textarea

```html
<textarea
  rows="3"
  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors"
></textarea>
```

#### Status Badges

```html
<!-- Approved/Success -->
<span
  class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border bg-green-900/30 text-green-400 border-green-700"
>
  <i class="fas fa-check-circle"></i>
  Approved
</span>

<!-- Pending -->
<span
  class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border bg-yellow-900/30 text-yellow-400 border-yellow-700"
>
  <i class="fas fa-clock"></i>
  Pending
</span>

<!-- Rejected/Error -->
<span
  class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border bg-red-900/30 text-red-400 border-red-700"
>
  <i class="fas fa-times-circle"></i>
  Rejected
</span>
```

#### Tables

```html
<table class="w-full">
  <thead class="bg-gray-900">
    <tr>
      <th
        class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider"
      >
        Column Header
      </th>
    </tr>
  </thead>
  <tbody class="divide-y divide-gray-700">
    <tr class="hover:bg-gray-750 transition-colors">
      <td class="px-6 py-4 whitespace-nowrap text-white">Cell Content</td>
    </tr>
  </tbody>
</table>
```

#### Stats Cards

```html
<div
  class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200"
>
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-gray-400 text-sm font-medium">Stat Title</h3>
    <i class="fas fa-icon text-red-500 text-2xl"></i>
  </div>
  <p class="text-4xl font-bold text-white">123</p>
  <p class="text-gray-400 text-sm mt-2">Description</p>
</div>
```

#### Alert Boxes

**Success Alert**

```html
<div
  class="bg-green-900/30 border border-green-700 text-green-400 px-4 py-3 rounded-lg mb-4 flex items-center"
>
  <i class="fas fa-check-circle mr-3"></i>
  <span>Success message</span>
</div>
```

**Error Alert**

```html
<div
  class="bg-red-900/30 border border-red-700 text-red-400 px-4 py-3 rounded-lg mb-4 flex items-center"
>
  <i class="fas fa-exclamation-circle mr-3"></i>
  <span>Error message</span>
</div>
```

**Info Box**

```html
<div class="p-4 bg-blue-900/20 border border-blue-700 rounded-lg">
  <h4 class="text-blue-400 font-semibold mb-2 flex items-center gap-2">
    <i class="fas fa-info-circle"></i>
    Information Title
  </h4>
  <p class="text-gray-400 text-sm">Information content</p>
</div>
```

**Warning Box**

```html
<div class="p-4 bg-yellow-900/20 border border-yellow-700 rounded-lg">
  <h4 class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
    <i class="fas fa-exclamation-triangle"></i>
    Warning Title
  </h4>
  <p class="text-gray-400 text-sm">Warning content</p>
</div>
```

### Layout Structure

#### Page Container

```html
<div class="p-6">
  <!-- Page content -->
</div>
```

#### Section Header

```html
<div class="mb-6">
  <h2 class="text-2xl font-bold text-white mb-2">Section Title</h2>
  <p class="text-gray-400">Section description</p>
</div>
```

#### Grid Layouts

**2 Columns**

```html
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Items -->
</div>
```

**3 Columns**

```html
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <!-- Items -->
</div>
```

**4 Columns**

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
  <!-- Items -->
</div>
```

### Icons (Font Awesome)

Common icons used:

- `fa-heartbeat` - Blood donation
- `fa-tint` - Blood drop
- `fa-hand-holding-heart` - Donate
- `fa-paper-plane` - Request
- `fa-warehouse` - Inventory
- `fa-check-circle` - Approved
- `fa-times-circle` - Rejected
- `fa-clock` - Pending
- `fa-user` - User
- `fa-users` - Users/recipients
- `fa-tachometer-alt` - Dashboard

### Spacing System

- `p-2` = 0.5rem (8px)
- `p-4` = 1rem (16px)
- `p-6` = 1.5rem (24px)
- `p-8` = 2rem (32px)

- `mb-2` = margin-bottom: 0.5rem
- `mb-4` = margin-bottom: 1rem
- `mb-6` = margin-bottom: 1.5rem

### Responsive Breakpoints

- `sm:` - 640px and up
- `md:` - 768px and up
- `lg:` - 1024px and up
- `xl:` - 1280px and up

### Usage Examples

#### Show/hide on mobile

```html
<!-- Hidden on mobile, visible on desktop -->
<div class="hidden md:block">Desktop only</div>

<!-- Visible on mobile, hidden on desktop -->
<div class="block md:hidden">Mobile only</div>
```

#### Responsive grid

```html
<!-- 1 column on mobile, 2 on tablet, 3 on desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
```

## Best Practices

1. **Consistency**: Use the same components throughout the application
2. **Accessibility**: Include proper labels and ARIA attributes
3. **Responsive**: Test on mobile, tablet, and desktop
4. **Performance**: Minimize custom CSS, use Tailwind utilities
5. **Dark Mode**: Stick to the gray color palette for backgrounds

## Common Patterns

### Action Links

```html
<a href="#" class="text-red-400 hover:text-red-300 transition-colors">
  Link Text
</a>
```

### Icon Buttons

```html
<button class="p-2 text-blue-400 hover:text-blue-300 transition-colors">
  <i class="fas fa-edit"></i>
</button>
```

### Empty States

```html
<div class="text-center py-12">
  <i class="fas fa-icon text-gray-600 text-6xl mb-4"></i>
  <p class="text-gray-400 text-xl">No items found</p>
  <p class="text-gray-500 text-sm mt-2">Description text</p>
</div>
```

---

This guide should help maintain consistency across the entire application!
