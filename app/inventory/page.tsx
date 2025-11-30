"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import UserNavbar from "../components/UserNavbar";
import { Package, Trash2 } from "lucide-react";

interface InventoryItem {
  id: number;
  user_id: number;
  item_name: string;
  item_image: string;
  item_value: number;
  acquired_from: string;
  acquired_at: string;
}

export default function InventoryPage() {
  const router = useRouter();
  const [items, setItems] = useState<InventoryItem[]>([]);
  const [selectedItems, setSelectedItems] = useState<number[]>([]);
  const [balance, setBalance] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const userStr = localStorage.getItem("user");
    if (!userStr) {
      router.push("/");
      return;
    }

    const user = JSON.parse(userStr);

    async function loadAll() {
      try {
        const inv = await fetch(
          `http://localhost/glimzyskins/backend/get-inventory.php?user_id=${user.id}`
        ).then((r) => r.json());

        const bal = await fetch(
          `http://localhost/glimzyskins/backend/get-user-balance.php?user_id=${user.id}`
        ).then((r) => r.json());

        setItems(inv.items || []);
        setBalance(parseFloat(bal.balance) || 0);
        setLoading(false);
      } catch (e) {
        console.error(e);
        setLoading(false);
      }
    }

    loadAll();
  }, [router]);

  const toggleSelect = (id: number) => {
    setSelectedItems((prev) =>
      prev.includes(id)
        ? prev.filter((x) => x !== id)
        : [...prev, id]
    );
  };

  const sellSelected = async () => {
    const userStr = localStorage.getItem("user");
    if (!userStr) return;

    const user = JSON.parse(userStr);

    const toSell = items.filter((item) => selectedItems.includes(item.id));

    const res = await fetch("http://localhost/glimzyskins/backend/sell-items.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        user_id: user.id,
        items: toSell
      })
    });

    const data = await res.json();

    if (data.success) {
      setItems(items.filter((i) => !selectedItems.includes(i.id)));
      setSelectedItems([]);
      setBalance((b) => b + data.total_value);
    }
  };

  if (loading)
    return (
      <div className="min-h-screen bg-neutral-900 flex items-center justify-center text-white">
        Ładowanie...
      </div>
    );

  return (
    <div className="min-h-screen bg-neutral-900 text-neutral-100">
      <UserNavbar active="inventory" balance={balance} />

      <div className="max-w-7xl mx-auto p-6">
        <div className="flex items-center justify-between mb-6">
          <div className="flex items-center gap-3">
            <Package className="w-8 h-8 text-amber-500" />
            <h1 className="text-3xl font-bold">Ekwipunek</h1>
          </div>

          {selectedItems.length > 0 && (
            <button
              onClick={sellSelected}
              className="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center gap-2"
            >
              <Trash2 className="w-5 h-5" />
              Sprzedaj ({selectedItems.length})
            </button>
          )}
        </div>

        {items.length === 0 ? (
          <p className="text-neutral-400">Brak przedmiotów.</p>
        ) : (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {items.map((item) => (
              <div
                key={item.id}
                onClick={() => toggleSelect(item.id)}
                className={`rounded-lg overflow-hidden border-2 cursor-pointer transition ${
                  selectedItems.includes(item.id)
                    ? "border-amber-500"
                    : "border-neutral-700"
                }`}
              >
                <div className="h-32 bg-neutral-900 flex items-center justify-center p-2">
                  <img
                    src={item.item_image}
                    className="w-full h-full object-contain"
                    alt={item.item_name}
                  />
                </div>

                <div className="p-3">
                  <h3 className="font-semibold text-sm text-neutral-100">
                    {item.item_name}
                  </h3>
                  <p className="text-neutral-400 text-xs">${item.item_value}</p>
                  <p className="text-neutral-500 text-xs">Z: {item.acquired_from}</p>
                </div>
              </div>
            ))}
          </div>
        )}

      </div>
    </div>
  );
}