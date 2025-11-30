"use client";

import Link from "next/link";
import { useEffect, useState } from "react";

type CaseItem = {
  id: number;
  name: string;
  description: string;
  price: number;
  image: string;
};

export default function CasesList() {
  const [casesData, setCasesData] = useState<CaseItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function load() {
      try {
        const res = await fetch("http://localhost/glimzyskins/backend/get-cases.php");
        const data = await res.json();
        setCasesData(data.cases || []);
      } catch (e) {
        console.error(e);
      } finally {
        setLoading(false);
      }
    }
    load();
  }, []);

  if (loading) return <div className="text-white p-6">Ładowanie...</div>;

  return (
    <div className="max-w-7xl mx-auto p-4">
      <h1 className="text-4xl font-bold text-neutral-100 mb-8">Dostępne Skrzynki</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {casesData.map((caseItem) => (
          <Link
            key={caseItem.id}
            href={`/cases/${caseItem.id}`}
            className="bg-neutral-800 rounded-xl overflow-hidden border border-neutral-700 hover:border-amber-600 transition group"
          >
            <div className="relative h-48 bg-neutral-950 flex items-center justify-center">
              <img
                src={caseItem.image}
                alt={caseItem.name}
                className="h-full w-full object-cover"
              />
            </div>

            <div className="p-6">
              <h3 className="text-xl font-bold text-neutral-100 mb-3">
                {caseItem.name}
              </h3>

              <p className="text-neutral-400 text-sm mb-4">
                {caseItem.description}
              </p>

              <div className="flex items-center justify-between">
                <span className="text-2xl font-bold text-amber-500">
                  {caseItem.price} zł
                </span>
                <button className="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                  Zobacz
                </button>
              </div>
            </div>
          </Link>
        ))}
      </div>
    </div>
  );
}