"use client";

import { useEffect, useState, useRef } from "react";
import UserNavbar from "../components/UserNavbar";
import { ChevronDown, ArrowUpDown, Calendar, DollarSign } from "lucide-react";

type HistoryEntry = {
  id: number;
  action_type: string;
  item_name: string | null;
  amount: number;
  created_at: string;
};

const sortOptions = [
  { value: "oldest", label: "Od najstarszych", icon: Calendar },
  { value: "newest", label: "Od najnowszych", icon: Calendar },
  { value: "cheapest", label: "Od najtańszych", icon: DollarSign },
  { value: "expensive", label: "Od najdroższych", icon: DollarSign },
];

export default function HistoryPage() {
  const [history, setHistory] = useState<HistoryEntry[]>([]);
  const [filtered, setFiltered] = useState<HistoryEntry[]>([]);
  const [balance, setBalance] = useState(0);
  const [loading, setLoading] = useState(true);

  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(sortOptions[1]);

  const dropdownRef = useRef<HTMLDivElement | null>(null);

  function getUserId() {
    if (typeof window === "undefined") return null;
    const user = localStorage.getItem("user");
    if (!user) return null;

    try {
      return JSON.parse(user)?.id ?? null;
    } catch {
      return null;
    }
  }

  function mapAction(type: string) {
    if (type === "sell_item" || type === "sell_won_item") return "SELL";
    return "UNKNOWN";
  }

  function getActionColor(type: string) {
    if (type === "sell_item" || type === "sell_won_item") return "text-red-400";
    return "text-neutral-300";
  }

  function getBackground(type: string) {
    if (type === "sell_item" || type === "sell_won_item")
      return "bg-red-500/10 border-red-500/30";
    return "bg-neutral-800 border-neutral-700";
  }

  function applySort(type: string, list: HistoryEntry[]) {
    let arr = [...list];

    if (type === "oldest") {
      arr.sort((a, b) => +new Date(a.created_at) - +new Date(b.created_at));
    } else if (type === "newest") {
      arr.sort((a, b) => +new Date(b.created_at) - +new Date(a.created_at));
    } else if (type === "cheapest") {
      arr.sort((a, b) => a.amount - b.amount);
    } else if (type === "expensive") {
      arr.sort((a, b) => b.amount - a.amount);
    }

    return arr;
  }

  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target as Node)) {
        setOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  useEffect(() => {
    const uid = getUserId();
    if (!uid) {
      setLoading(false);
      return;
    }

    async function loadData() {
      try {
        
        const hisRes = await fetch(
          `http://localhost/glimzyskins/backend/get-history.php?user_id=${uid}`
        );
        const hisData = await hisRes.json();
        const rawHistory = hisData.history || [];

        
        const sells = rawHistory.filter(
          (h: any) => h.action_type === "sell_item" || h.action_type === "sell_won_item"
        );

        const normalized: HistoryEntry[] = sells.map((h: any) => ({
          id: Number(h.id),
          action_type: String(h.action_type),
          item_name: h.item_name ?? null,
          amount: Number(h.amount ?? 0),
          created_at: h.created_at ?? "",
        }));

        setHistory(normalized);
        setFiltered(normalized);

        
        const balRes = await fetch(
          `http://localhost/glimzyskins/backend/get-user-balance.php?user_id=${uid}`
        );
        if (balRes.ok) {
          const balData = await balRes.json();
          setBalance(parseFloat(balData.balance ?? 0) || 0);
        }
      } catch (e) {
        console.error("History Load Error:", e);
        setHistory([]);
        setFiltered([]);
      } finally {
        setLoading(false);
      }
    }

    loadData();
  }, []);

  useEffect(() => {
    setFiltered(applySort(selected.value, history));
  }, [selected, history]);

  if (loading)
    return (
      <div className="min-h-screen bg-neutral-900 text-white flex items-center justify-center">
        Ładowanie...
      </div>
    );

  return (
    <div className="min-h-screen bg-neutral-900 text-white">
      <UserNavbar active="history" balance={balance} />

      <div className="p-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold">Historia — Sprzedaże</h1>

          <div className="relative" ref={dropdownRef}>
            <button
              onClick={() => setOpen(!open)}
              className="flex items-center gap-2 px-4 py-2 bg-neutral-800 border border-neutral-700 hover:border-neutral-600 rounded-xl text-neutral-200 hover:text-white transition shadow-md"
            >
              <ArrowUpDown size={18} />
              {selected.label}
              <ChevronDown size={18} className={`transition ${open ? "rotate-180" : ""}`} />
            </button>

            {open && (
              <div className="absolute right-0 w-56 mt-2 bg-neutral-800 border border-neutral-700 rounded-xl shadow-xl overflow-hidden z-50">
                {sortOptions.map((opt) => (
                  <div
                    key={opt.value}
                    onClick={() => {
                      setSelected(opt);
                      setOpen(false);
                    }}
                    className="flex items-center gap-3 px-4 py-3 hover:bg-neutral-700 cursor-pointer transition text-neutral-200"
                  >
                    <opt.icon size={18} />
                    {opt.label}
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        {filtered.length === 0 ? (
          <p className="text-neutral-400">Brak sprzedaży.</p>
        ) : (
          <div className="flex flex-col gap-4">
            {filtered.map((h) => (
              <div
                key={h.id}
                className={`p-4 rounded-xl border ${getBackground(h.action_type)} 
                flex flex-col gap-1 shadow-lg`}
              >
                <div className="flex items-center justify-between">
                  <p className={`text-xl font-bold ${getActionColor(h.action_type)}`}>
                    SELL
                  </p>

                  <span className="text-neutral-400 text-sm">{h.created_at}</span>
                </div>

                <p className="text-neutral-200 text-lg font-semibold">
                  {h.item_name ?? "Nieznany przedmiot"}
                </p>

                <p className="text-neutral-300 text-md mt-1">
                  Cena:{" "}
                  <span className="font-bold">
                    {Number(h.amount || 0).toFixed(2)} zł
                  </span>
                </p>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}