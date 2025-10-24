<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
  fg: { type: String, default: '#00ff00' },
  bg: { type: String, default: '#000000' },
  opacity: { type: Number, default: 0.85 },
  speed: { type: Number, default: 1.0 }, // multiplier
  density: { type: Number, default: 20 }, // columns per ~1000px
});

const canvas = ref(null);
let ctx, w, h, fontSize, columns, drops;
let raf;

function init() {
  const c = canvas.value;
  if (!c) return;
  c.width = w = window.innerWidth;
  c.height = h = window.innerHeight;
  ctx = c.getContext('2d');
  // Interpret density as ~columns per 1000px of width
  columns = Math.max(5, Math.floor((w / 1000) * Math.max(props.density || 20, 5)));
  fontSize = Math.max(12, Math.floor(w / columns));
  drops = Array(columns).fill(1);
  ctx.font = `${fontSize}px monospace`;
}

const chars = 'アイウエオカキクケコサシスセソ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

function draw() {
  // fade background
  ctx.fillStyle = hexToRgba(props.bg, props.opacity);
  ctx.fillRect(0, 0, w, h);

  ctx.fillStyle = props.fg;
  for (let i = 0; i < drops.length; i++) {
    const text = chars.charAt(Math.floor(Math.random() * chars.length));
    ctx.fillText(text, i * fontSize, drops[i] * fontSize);

    if (drops[i] * fontSize > h && Math.random() > 0.975) {
      drops[i] = 0;
    }
    drops[i] += props.speed;
  }
  raf = requestAnimationFrame(draw);
}

function hexToRgba(hex, a) {
  const m = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex || '#000000');
  const r = parseInt(m?.[1] || '00', 16);
  const g = parseInt(m?.[2] || '00', 16);
  const b = parseInt(m?.[3] || '00', 16);
  return `rgba(${r}, ${g}, ${b}, ${a})`;
}

function start() {
  cancel();
  init();
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  raf = requestAnimationFrame(draw);
}
function cancel() { if (raf) cancelAnimationFrame(raf); }

onMounted(() => {
  start();
  window.addEventListener('resize', start);
});
onBeforeUnmount(() => {
  cancel();
  window.removeEventListener('resize', start);
});

watch(() => [props.fg, props.bg, props.opacity, props.speed, props.density], start);
</script>

<template>
  <canvas ref="canvas" class="h-full w-full"></canvas>
</template>
