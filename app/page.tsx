"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";
import WindowManager from "./components/WindowManager";

export default function HomePage() {
  const router = useRouter();

  useEffect(() => {
    if (typeof window === "undefined") return;

    const user = localStorage.getItem("user");
    if (user) {
      router.push("/dashboardCases");
    }
  }, [router]);

  return (
    <div>
      <WindowManager />
    </div>
  );
}